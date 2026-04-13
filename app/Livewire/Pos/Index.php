<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\ProductItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    
    // Cart state
    public $cart = [];
    
    // Global discount & costs
    public $global_discount = 0;
    public $biaya_ongkir = 0;
    public $biaya_packing = 0;

    // Customer
    public $customer_id = '';
    public $customer_search = '';
    public $customer_name = '';
    public $customer_wa = '';
    public $customer_alamat = '';
    public $customer_catatan = '';

    // Payment Info
    public $payment_method = 'Cash'; // Cash, Transfer BCA, Transfer BRI, Transfer Mandiri
    public $status_ongkir = 'Customer'; // Admin/Customer
    public $status_packing = 'Customer'; // Admin/Customer
    public $transaksi_catatan = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function addToCart($product_item_id)
    {
        $item = ProductItem::with(['product', 'variantOption1', 'variantOption2'])->find($product_item_id);
        
        if(!$item || $item->stok_akhir < 1) {
            $this->addError('cart', 'Stok produk tidak mencukupi.');
            return;
        }

        $variantName = 'Standard';
        if($item->variantOption1 || $item->variantOption2) {
            $variantName = ($item->variantOption1 ? $item->variantOption1->value : '') . 
                           ($item->variantOption2 ? ' / ' . $item->variantOption2->value : '');
        }

        // Check if already in cart
        $exists = false;
        foreach($this->cart as $k => $c) {
            if($c['id'] == $item->id) {
                if($c['qty'] + 1 > $item->stok_akhir) {
                    $this->addError('cart', 'Maksimal stok tercapai untuk ' . $c['nama']);
                    return;
                }
                $this->cart[$k]['qty']++;
                $exists = true;
                break;
            }
        }

        if(!$exists) {
            $this->cart[] = [
                'id' => $item->id,
                'nama' => $item->product->nama_produk,
                'varian' => trim($variantName, ' /'),
                'stok' => $item->stok_akhir,
                'harga_modal' => $item->harga_modal,
                'harga_jual' => $item->harga_jual,
                'harga_grosir' => $item->harga_sell,
                'harga_aktif' => $item->harga_jual,
                'diskon_item' => 0,
                'qty' => 1
            ];
        }
    }

    public function updateQty($index, $action)
    {
        if($action === 'plus') {
            if($this->cart[$index]['qty'] + 1 > $this->cart[$index]['stok']) {
                 $this->addError('cart', 'Maksimal stok tercapai.');
                 return;
            }
            $this->cart[$index]['qty']++;
        } elseif($action === 'minus') {
            $this->cart[$index]['qty']--;
            if($this->cart[$index]['qty'] <= 0) {
                $this->removeCart($index);
            }
        }
    }

    public function removeCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart); // reindex
    }

    public function setHargaGrosir($index)
    {
        $this->cart[$index]['harga_aktif'] = $this->cart[$index]['harga_grosir'];
    }

    public function setHargaRetail($index)
    {
         $this->cart[$index]['harga_aktif'] = $this->cart[$index]['harga_jual'];
    }

    public function updatedCustomerId($id)
    {
        if($id) {
            $c = Customer::find($id);
            if (!$c) {
                $this->customer_id = '';
                return;
            }
            $this->customer_name = $c->nama_customer ?? '';
            $this->customer_wa = $c->no_whatsapp ?? '';
            $this->customer_alamat = $c->alamat ?? '';
            $this->customer_catatan = $c->catatan ?? '';
        } else {
            $this->customer_name = '';
            $this->customer_wa = '';
            $this->customer_alamat = '';
            $this->customer_catatan = '';
        }
    }

    public function selectCustomerFromList($id)
    {
        $this->customer_id = (string) $id;
        $this->updatedCustomerId($id);

        if ($this->customer_id) {
            $label = trim($this->customer_name);
            if ($this->customer_wa) {
                $label .= ' - ' . $this->customer_wa;
            }
            $this->customer_search = $label;
        }
    }

    public function getSubtotalProperty()
    {
        $total = 0;
        foreach($this->cart as $item) {
            $potongan = (int)$item['diskon_item'] ?: 0;
            $hargaAfterDiscount = $item['harga_aktif'] - $potongan;
            $total += ($hargaAfterDiscount * $item['qty']);
        }
        return $total;
    }

    public function getTotalNettoProperty()
    {
        $total = $this->subtotal - (int)$this->global_discount;
        
        if($this->status_ongkir == 'Customer') $total += (int)$this->biaya_ongkir;
        if($this->status_packing == 'Customer') $total += (int)$this->biaya_packing;
        
        return $total;
    }

    public function processCheckout()
    {
        if(count($this->cart) == 0) {
            $this->addError('cart', 'Keranjang masih kosong.');
            return;
        }

        DB::beginTransaction();
        try {
            $cust_id = null;
            if($this->customer_id) {
                $cust_id = (int) $this->customer_id;
            } elseif (
                trim((string) $this->customer_name) !== '' ||
                trim((string) $this->customer_wa) !== '' ||
                trim((string) $this->customer_alamat) !== '' ||
                trim((string) $this->customer_catatan) !== ''
            ) {
                $cName = trim($this->customer_name) ?: 'Pelanggan Umum';
                $newCust = Customer::create([
                    'nama_customer' => $cName,
                    'no_whatsapp' => $this->customer_wa,
                    'alamat' => $this->customer_alamat,
                    'catatan' => $this->customer_catatan
                ]);
                $cust_id = $newCust->id;
            }

            $trx = Transaction::create([
                'no_invoice' => 'INV' . date('ymd') . strtoupper(Str::random(4)),
                'customer_id' => $cust_id,
                'total_bruto' => $this->subtotal,
                'total_diskon' => (int)$this->global_discount,
                'biaya_ongkir' => (int)$this->biaya_ongkir,
                'biaya_packing' => (int)$this->biaya_packing,
                'status_ongkir' => $this->status_ongkir,
                'status_packing' => $this->status_packing,
                'total_netto' => $this->totalNetto,
                'payment_method' => $this->payment_method,
                'catatan' => $this->transaksi_catatan,
                'status' => 'Selesai'
            ]);

            foreach($this->cart as $item) {
                $potongan = (int)$item['diskon_item'] ?: 0;
                $harga_jual_final = (int)$item['harga_aktif'] - $potongan;
                
                TransactionItem::create([
                    'transaction_id' => $trx->id,
                    'product_item_id' => $item['id'],
                    'qty' => $item['qty'],
                    'subtotal' => $harga_jual_final * $item['qty'],
                    'nama_produk_history' => $item['nama'],
                    'varian_history' => $item['varian'],
                    'harga_modal_history' => $item['harga_modal'],
                    'harga_jual_history' => $harga_jual_final
                ]);

                $dbItem = ProductItem::find($item['id']);
                $dbItem->stok_akhir = max(0, $dbItem->stok_akhir - $item['qty']); // Prevent negative just in case
                $dbItem->save();
            }

            DB::commit();

            session()->flash('success', 'Transaksi berhasil disimpan!');
            return redirect()->to('/transactions');

        } catch(\Exception $e) {
            DB::rollback();
            $this->addError('cart', 'Gagal checkout: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $products = Product::with(['category', 'items.variantOption1', 'items.variantOption2'])
            ->where(function($query) {
                $query->where('nama_produk', 'like', '%'.$this->search.'%')
                      ->orWhere('kode_produk', 'like', '%'.$this->search.'%');
            })
            ->whereHas('items', function($q){
                 $q->where('stok_akhir', '>', 0);
            })
            ->orderBy('id', 'desc')
            ->paginate(12);

        $search = trim((string) $this->customer_search);
        $customers = Customer::query()
            ->withCount('transactions')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_customer', 'like', '%' . $search . '%')
                        ->orWhere('no_whatsapp', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('transactions_count')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('livewire.pos.index', [
            'products' => $products,
            'customers' => $customers
        ])->layout('layouts.app');
    }
}
