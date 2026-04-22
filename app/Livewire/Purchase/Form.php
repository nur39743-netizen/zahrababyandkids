<?php

namespace App\Livewire\Purchase;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\ProductItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $purchase_id;
    public $supplier_id;
    public $reference_number;
    public $purchase_date;
    public $status = 'pending'; // pending, ordered, received
    public $payment_status = 'unpaid'; // unpaid, partial, paid
    public $shipping_cost = 0;
    public $amount_paid = 0;
    public $notes;
    
    public $items = []; // array of items in cart
    
    // For product search
    public $search = '';
    public $searchResults = [];

    public function mount($id = null)
    {
        if ($id) {
            $purchase = Purchase::with('purchaseItems.productItem.product', 'purchaseItems.productItem.variantOption1', 'purchaseItems.productItem.variantOption2')->findOrFail($id);
            $this->purchase_id = $purchase->id;
            $this->supplier_id = $purchase->supplier_id;
            $this->reference_number = $purchase->reference_number;
            $this->purchase_date = $purchase->purchase_date->format('Y-m-d');
            $this->status = $purchase->status;
            $this->payment_status = $purchase->payment_status;
            $this->shipping_cost = $purchase->shipping_cost;
            $this->amount_paid = $purchase->amount_paid;
            $this->notes = $purchase->notes;

            foreach ($purchase->purchaseItems as $item) {
                $this->items[] = [
                    'product_item_id' => $item->product_item_id,
                    'name' => $item->productItem->product->nama_produk,
                    'variant' => $item->productItem->variantString(),
                    'quantity' => $item->quantity,
                    'unit_cost' => $item->unit_cost,
                    'subtotal' => $item->subtotal
                ];
            }
        } else {
            $this->purchase_date = date('Y-m-d');
        }
    }

    public function updatedSearch()
    {
        if (strlen($this->search) > 1) {
            $this->searchResults = ProductItem::with(['product', 'variantOption1', 'variantOption2'])
                ->whereHas('product', function($q) {
                    $q->where('nama_produk', 'like', '%' . $this->search . '%');
                })
                ->take(10)
                ->get();
        } else {
            $this->searchResults = [];
        }
    }

    public function addItem($productItemId)
    {
        $productItem = ProductItem::with(['product', 'variantOption1', 'variantOption2'])->find($productItemId);
        if (!$productItem) return;

        // Check if already in cart
        $exists = false;
        foreach ($this->items as $index => $item) {
            if ($item['product_item_id'] == $productItemId) {
                $this->items[$index]['quantity']++;
                $this->calculateSubtotal($index);
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $this->items[] = [
                'product_item_id' => $productItem->id,
                'name' => $productItem->product->nama_produk,
                'variant' => $productItem->variantString(),
                'quantity' => 1,
                'unit_cost' => $productItem->harga_modal > 0 ? $productItem->harga_modal : 0,
                'subtotal' => $productItem->harga_modal > 0 ? $productItem->harga_modal : 0,
            ];
        }

        $this->search = '';
        $this->searchResults = [];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function calculateSubtotal($index)
    {
        $qty = (int) ($this->items[$index]['quantity'] ?: 0);
        $cost = (float) ($this->items[$index]['unit_cost'] ?: 0);
        $this->items[$index]['subtotal'] = $qty * $cost;
        
        // Ensure paid amount and payment status update correctly based on new totals
        $this->calculatePaymentStatus();
    }

    public function calculatePaymentStatus()
    {
        $grandTotal = $this->getTotalAmountProperty() + (float) ($this->shipping_cost ?: 0);
        $paid = (float) ($this->amount_paid ?: 0);

        if ($paid <= 0) {
            $this->payment_status = 'unpaid';
        } elseif ($paid < $grandTotal) {
            $this->payment_status = 'partial';
        } else {
            $this->payment_status = 'paid';
        }
    }

    public function updatedPaymentStatus()
    {
        $grandTotal = $this->getTotalAmountProperty() + (float) ($this->shipping_cost ?: 0);
        if ($this->payment_status === 'paid') {
            $this->amount_paid = $grandTotal;
        } elseif ($this->payment_status === 'unpaid') {
            $this->amount_paid = 0;
        }
    }

    public function updatedAmountPaid()
    {
        $this->calculatePaymentStatus();
    }

    public function updatedShippingCost()
    {
        $this->calculatePaymentStatus();
    }

    public function getTotalAmountProperty()
    {
        return collect($this->items)->sum('subtotal');
    }

    public function save()
    {
        $this->validate([
            'supplier_id' => 'required',
            'purchase_date' => 'required|date',
            'status' => 'required|in:pending,ordered,received',
            'items' => 'required|array|min:1',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ], [
            'supplier_id.required' => 'Supplier harus dipilih',
            'items.required' => 'Keranjang tidak boleh kosong',
        ]);

        if ($this->purchase_id) {
            $purchase = Purchase::find($this->purchase_id);
            // Protect if already received
            if ($purchase->status === 'received' && $this->status !== 'received') {
                session()->flash('error', 'Pembelian yang sudah diterima tidak dapat diubah statusnya menjadi draft/dipesan.');
                return;
            }
        }

        DB::beginTransaction();
        try {
            $purchaseData = [
                'supplier_id' => $this->supplier_id,
                'reference_number' => $this->reference_number,
                'purchase_date' => $this->purchase_date,
                'status' => $this->status,
                'payment_status' => $this->payment_status,
                'total_amount' => $this->getTotalAmountProperty(),
                'shipping_cost' => $this->shipping_cost ?: 0,
                'amount_paid' => $this->amount_paid ?: 0,
                'notes' => $this->notes,
            ];

            if ($this->purchase_id) {
                $purchase = Purchase::find($this->purchase_id);
                $purchase->update($purchaseData);
                $purchase->purchaseItems()->delete(); // re-create below
            } else {
                $purchase = Purchase::create($purchaseData);
            }

            foreach ($this->items as $item) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_item_id' => $item['product_item_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'subtotal' => $item['subtotal'],
                ]);

                // Update stock if status is received
                if ($this->status === 'received' && (!$this->purchase_id || ($this->purchase_id && Purchase::find($this->purchase_id)->getOriginal('status') !== 'received'))) {
                    $pItem = ProductItem::find($item['product_item_id']);
                    if ($pItem) {
                        $pItem->stok_akhir += $item['quantity'];
                        // Optional: update harga_modal (we can just update if we want)
                        $pItem->harga_modal = $item['unit_cost'];
                        $pItem->save();
                    }
                }
            }

            DB::commit();
            session()->flash('success', 'Transaksi pembelian berhasil disimpan.');
            return redirect('/purchases');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.purchase.form', [
            'suppliers' => Supplier::orderBy('name')->get()
        ])->layout('layouts.app');
    }
}
