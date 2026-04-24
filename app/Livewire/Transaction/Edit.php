<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\ProductItem;
use App\Models\Product;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\DB;

class Edit extends Component
{
    public Transaction $transaction;
    public $customer_id;
    public $payment_method;
    /** @var string lunas|belum_lunas */
    public $status_pembayaran;
    public $transaction_date;
    public $status_ongkir;
    public $status_packing;
    public $catatan;
    public $status;
    public $items = [];
    public $originalItems = [];

    // For adding new items
    public $search = '';
    public $selectedProduct = null;
    public $newItemQty = 1;
    public $newItemDiskon = 0;

    // Modal states
    public $showEditModal = false;
    public $editingIndex = null;
    public $editQty;
    public $editDiskon;
    public $editMaxQty = 1;
    public $editSearch = '';
    public $editProductItemId = null;
    public $showAddModal = false;

    public function mount($id)
    {
        $transaction = Transaction::with(['customer', 'items.productItem.product'])->findOrFail($id);
        $this->transaction = $transaction;
        $this->customer_id = $transaction->customer_id;
        $this->payment_method = $transaction->payment_method;
        $this->status_pembayaran = $transaction->status_pembayaran
            ?? Transaction::STATUS_PEMBAYARAN_LUNAS;
        $this->transaction_date = optional($transaction->transaction_date)->toDateString() ?: optional($transaction->created_at)->toDateString();
        $this->status_ongkir = $transaction->status_ongkir;
        $this->status_packing = $transaction->status_packing;
        $this->catatan = $transaction->catatan;
        $this->status = $transaction->status;

        foreach ($transaction->items as $item) {
            $this->items[] = [
                'id' => $item->id, // transaction_item id
                'product_item_id' => $item->product_item_id,
                'qty' => $item->qty,
                'diskon_item' => $item->diskon_item,
                'harga_modal_history' => $item->harga_modal_history,
                'harga_jual_history' => $item->harga_jual_history,
                'subtotal' => $item->subtotal,
                'nama_produk_history' => $item->nama_produk_history,
                'varian_history' => $item->varian_history,
                'is_new' => false, // flag for new items
            ];
        }

        $this->originalItems = json_decode(json_encode($this->items), true);
    }

    public function selectProduct($productItemId)
    {
        $productItem = ProductItem::with(['product', 'variantOption1', 'variantOption2'])->find($productItemId);
        if ($productItem && $productItem->stok_akhir > 0) {
            $this->selectedProduct = $productItem;
            $this->newItemQty = 1;
            $this->newItemDiskon = 0;
        }
    }

    public function addItem()
    {
        if (!$this->selectedProduct) return;

        $this->validate([
            'newItemQty' => 'required|integer|min:1',
            'newItemDiskon' => 'nullable|numeric|min:0',
        ]);

        // Check if already in items
        $exists = collect($this->items)->firstWhere('product_item_id', $this->selectedProduct->id);
        if ($exists) {
            $this->addError('add', 'Produk ini sudah ada dalam transaksi.');
            return;
        }

        if ((int) $this->newItemQty > (int) $this->selectedProduct->stok_akhir) {
            $this->addError('add', 'Qty melebihi stok tersedia.');
            return;
        }

        $this->items[] = [
            'id' => null, // new item
            'product_item_id' => $this->selectedProduct->id,
            'qty' => $this->newItemQty,
            'diskon_item' => $this->newItemDiskon,
            'harga_modal_history' => $this->selectedProduct->harga_modal,
            'harga_jual_history' => $this->selectedProduct->harga_jual,
            'subtotal' => ($this->selectedProduct->harga_jual - $this->newItemDiskon) * $this->newItemQty,
            'nama_produk_history' => $this->selectedProduct->product->nama_produk,
            'varian_history' => ($this->selectedProduct->variantOption1 ? $this->selectedProduct->variantOption1->value : '') .
                ($this->selectedProduct->variantOption2 ? ' / ' . $this->selectedProduct->variantOption2->value : ''),
            'is_new' => true,
        ];

        $this->selectedProduct = null;
        $this->search = '';
        $this->closeAddModal();
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function editItem($index)
    {
        $this->editingIndex = $index;
        $this->editQty = $this->items[$index]['qty'];
        $this->editDiskon = $this->items[$index]['diskon_item'];
        $this->editProductItemId = $this->items[$index]['product_item_id'];
        $productItem = ProductItem::with(['product', 'variantOption1', 'variantOption2'])->find($this->editProductItemId);
        $this->editSearch = $productItem
            ? trim($productItem->product->nama_produk . ' - ' . $productItem->variantString())
            : '';
        $this->editMaxQty = $productItem ? ((int) $productItem->stok_akhir + (int) $this->items[$index]['qty']) : (int) $this->items[$index]['qty'];
        $this->showEditModal = true;
    }

    public function updatedEditProductItemId($value)
    {
        if (!$value || $this->editingIndex === null || !isset($this->items[$this->editingIndex])) {
            return;
        }

        $currentItem = $this->items[$this->editingIndex];
        $selectedProductItem = ProductItem::with(['product', 'variantOption1', 'variantOption2'])->find($value);
        if (!$selectedProductItem) {
            return;
        }

        $isSameItem = (int) $currentItem['product_item_id'] === (int) $value;
        $currentQty = (int) ($currentItem['qty'] ?? 0);
        $availableQty = (int) $selectedProductItem->stok_akhir;
        $this->editMaxQty = $isSameItem ? $availableQty + $currentQty : $availableQty;

        $this->editSearch = trim($selectedProductItem->product->nama_produk . ' - ' . $selectedProductItem->variantString());
    }

    public function saveEdit()
    {
        $this->validate([
            'editQty' => 'required|integer|min:1',
            'editDiskon' => 'nullable|numeric|min:0',
            'editProductItemId' => 'required|integer|exists:product_items,id',
        ]);

        if (!isset($this->items[$this->editingIndex])) {
            $this->addError('update', 'Item tidak ditemukan.');
            return;
        }

        $editingItem = $this->items[$this->editingIndex];
        $dbProductItem = ProductItem::with(['product', 'variantOption1', 'variantOption2'])->find($this->editProductItemId);
        if (!$dbProductItem || !$dbProductItem->product) {
            $this->addError('update', 'Produk item tidak ditemukan.');
            return;
        }

        $oldQty = (int) ($editingItem['qty'] ?? 0);
        $isSameItem = (int) $editingItem['product_item_id'] === (int) $this->editProductItemId;
        $maxQty = $isSameItem ? ((int) $dbProductItem->stok_akhir + $oldQty) : (int) $dbProductItem->stok_akhir;
        if ((int) $this->editQty > $maxQty) {
            $this->addError('editQty', 'Qty melebihi stok yang tersedia.');
            return;
        }

        $duplicate = collect($this->items)
            ->except($this->editingIndex)
            ->contains(fn($item) => (int) $item['product_item_id'] === (int) $this->editProductItemId);
        if ($duplicate) {
            $this->addError('update', 'Produk item tersebut sudah ada di daftar transaksi.');
            return;
        }

        $this->items[$this->editingIndex]['product_item_id'] = (int) $this->editProductItemId;
        $this->items[$this->editingIndex]['qty'] = $this->editQty;
        $this->items[$this->editingIndex]['diskon_item'] = $this->editDiskon;
        $this->items[$this->editingIndex]['harga_modal_history'] = (int) $dbProductItem->harga_modal;
        $this->items[$this->editingIndex]['harga_jual_history'] = (int) $dbProductItem->harga_jual;
        $this->items[$this->editingIndex]['nama_produk_history'] = $dbProductItem->product->nama_produk;
        $this->items[$this->editingIndex]['varian_history'] = $dbProductItem->variantString();
        $this->items[$this->editingIndex]['subtotal'] = ($this->items[$this->editingIndex]['harga_jual_history'] - $this->editDiskon) * $this->editQty;
        $this->editProductItemId = null;
        $this->editSearch = '';
        $this->showEditModal = false;
    }

    public function openAddModal()
    {
        $this->showAddModal = true;
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->selectedProduct = null;
        $this->search = '';
    }

    public function cancelEdit()
    {
        $this->showEditModal = false;
        $this->editingIndex = null;
        $this->editProductItemId = null;
        $this->editSearch = '';
    }

    public function saveTransaction()
    {
        $this->validate([
            'payment_method' => 'required|string',
            'status_pembayaran' => 'required|in:' . Transaction::STATUS_PEMBAYARAN_LUNAS . ',' . Transaction::STATUS_PEMBAYARAN_BELUM,
            'transaction_date' => 'required|date',
            'status_ongkir' => 'required|string',
            'status_packing' => 'required|string',
            'status' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // Update transaction header
            $this->transaction->update([
                'customer_id' => $this->customer_id ?: null,
                'payment_method' => $this->payment_method,
                'status_pembayaran' => $this->status_pembayaran,
                'transaction_date' => $this->transaction_date,
                'status_ongkir' => $this->status_ongkir,
                'status_packing' => $this->status_packing,
                'catatan' => $this->catatan,
                'status' => $this->status,
            ]);

            // Get current and original item IDs
            $currentIds = collect($this->items)->pluck('id')->filter()->values()->toArray();
            $originalIds = collect($this->originalItems)->pluck('id')->values()->toArray();
            $removedIds = array_diff($originalIds, $currentIds);

            // Delete removed items and restore stock
            foreach ($removedIds as $removedId) {
                $removedItem = $this->transaction->items()->find($removedId);
                if ($removedItem) {
                    $removedItem->productItem->increment('stok_akhir', $removedItem->qty);
                    $removedItem->delete();
                }
            }

            // Process items: update existing and add new
            $total_bruto = 0;
            $total_diskon = 0;

            foreach ($this->items as $item) {
                $harga_jual_final = $item['harga_jual_history'] - ($item['diskon_item'] ?: 0);
                $subtotal = $harga_jual_final * $item['qty'];

                if (isset($item['id']) && $item['id']) {
                    // Update existing item
                    $existingItem = $this->transaction->items()->find($item['id']);
                    if ($existingItem) {
                        $oldProductItem = $existingItem->productItem;
                        if (!$oldProductItem) {
                            throw new \Exception('Produk item tidak ditemukan untuk item transaksi.');
                        }

                        $isProductChanged = (int) $existingItem->product_item_id !== (int) $item['product_item_id'];
                        if ($isProductChanged) {
                            $newProductItem = ProductItem::find($item['product_item_id']);
                            if (!$newProductItem) {
                                throw new \Exception('Produk item pengganti tidak ditemukan.');
                            }
                            if ((int) $item['qty'] > (int) $newProductItem->stok_akhir) {
                                throw new \Exception('Stok produk pengganti tidak cukup untuk ' . $item['nama_produk_history'] . '.');
                            }

                            $oldProductItem->increment('stok_akhir', (int) $existingItem->qty);
                            $newProductItem->decrement('stok_akhir', (int) $item['qty']);
                        } else {
                            $qtyDiff = $item['qty'] - $existingItem->qty;
                            if ($qtyDiff > 0 && $oldProductItem->stok_akhir < $qtyDiff) {
                                throw new \Exception('Stok tidak cukup untuk menambah qty ' . $item['nama_produk_history'] . '.');
                            }
                            if ($qtyDiff > 0) {
                                $oldProductItem->decrement('stok_akhir', $qtyDiff);
                            } elseif ($qtyDiff < 0) {
                                $oldProductItem->increment('stok_akhir', abs($qtyDiff));
                            }
                        }

                        $existingItem->update([
                            'product_item_id' => $item['product_item_id'],
                            'qty' => $item['qty'],
                            'diskon_item' => $item['diskon_item'] ?: 0,
                            'harga_modal_history' => $item['harga_modal_history'] ?? $existingItem->harga_modal_history,
                            'harga_jual_history' => $harga_jual_final,
                            'subtotal' => $subtotal,
                            'nama_produk_history' => $item['nama_produk_history'],
                            'varian_history' => $item['varian_history'],
                        ]);
                    }
                } else {
                    // Add new item
                    $productItem = ProductItem::find($item['product_item_id']);
                    if (!$productItem) {
                        throw new \Exception('Produk item baru tidak ditemukan.');
                    }
                    if ((int) $item['qty'] > (int) $productItem->stok_akhir) {
                        throw new \Exception('Stok tidak cukup untuk ' . $item['nama_produk_history'] . '.');
                    }

                    TransactionItem::create([
                        'transaction_id' => $this->transaction->id,
                        'product_item_id' => $item['product_item_id'],
                        'qty' => $item['qty'],
                        'diskon_item' => $item['diskon_item'] ?: 0,
                        'harga_modal_history' => $item['harga_modal_history'] ?? $productItem->harga_modal,
                        'harga_jual_history' => $harga_jual_final,
                        'subtotal' => $subtotal,
                        'nama_produk_history' => $item['nama_produk_history'],
                        'varian_history' => $item['varian_history'],
                    ]);

                    // Decrease stock
                    $productItem->decrement('stok_akhir', $item['qty']);
                }

                $total_bruto += $item['harga_jual_history'] * $item['qty'];
                $total_diskon += ($item['diskon_item'] ?: 0) * $item['qty'];
            }

            // Recalculate totals
            $total_netto = $total_bruto - $total_diskon;
            if ($this->status_ongkir == 'Customer') $total_netto += $this->transaction->biaya_ongkir;
            if ($this->status_packing == 'Customer') $total_netto += $this->transaction->biaya_packing;

            $this->transaction->update([
                'total_bruto' => $total_bruto,
                'total_diskon' => $total_diskon,
                'total_netto' => $total_netto,
            ]);

            DB::commit();
            session()->flash('success', 'Transaksi berhasil diperbarui.');
            return redirect('/transactions/' . $this->transaction->id);
        } catch (\Exception $e) {
            DB::rollback();
            $this->addError('update', 'Gagal update transaksi: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $editSearch = trim((string) $this->editSearch);
        $editProductItems = ProductItem::with(['product', 'variantOption1', 'variantOption2'])
            ->whereHas('product', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->when($editSearch !== '', function ($query) use ($editSearch) {
                $query->where(function ($q) use ($editSearch) {
                    $q->whereHas('product', function ($p) use ($editSearch) {
                        $p->where('nama_produk', 'like', '%' . $editSearch . '%');
                    })->orWhereHas('variantOption1', function ($v) use ($editSearch) {
                        $v->where('value', 'like', '%' . $editSearch . '%');
                    })->orWhereHas('variantOption2', function ($v) use ($editSearch) {
                        $v->where('value', 'like', '%' . $editSearch . '%');
                    });
                });
            })
            ->orderByDesc('id')
            ->where(function ($query) {
                $query->where('stok_akhir', '>', 0);
                if ($this->editProductItemId) {
                    $query->orWhere('id', $this->editProductItemId);
                }
            })
            ->limit(20)
            ->get();

        $productItems = ProductItem::with(['product', 'variantOption1', 'variantOption2'])
            ->where('stok_akhir', '>', 0)
            ->whereHas('product', function ($q) {
                $q->where('nama_produk', 'like', '%' . $this->search . '%')
                    ->whereNull('deleted_at');
            })
            ->get();

        $products = Product::with(['items' => function ($q) {
            $q->where('stok_akhir', '>', 0);
        }])->where('nama_produk', 'like', '%' . $this->search . '%')->whereNull('deleted_at')->get();

        return view('livewire.transaction.edit', [
            'customers' => Customer::all(),
            'paymentMethods' => ['Cash', 'Transfer BCA', 'Transfer BRI', 'Transfer Mandiri'],
            'shippingOptions' => ['Customer', 'Admin'],
            'packingOptions' => ['Customer', 'Admin'],
            'statusOptions' => ['Selesai', 'Pending', 'Dibatalkan'],
            'statusPembayaranOptions' => [
                Transaction::STATUS_PEMBAYARAN_LUNAS => 'Sudah lunas',
                Transaction::STATUS_PEMBAYARAN_BELUM => 'Belum lunas (piutang)',
            ],
            'productItems' => $productItems,
            'products' => $products,
            'editProductItems' => $editProductItems,
        ])->layout('layouts.app');
    }
}
