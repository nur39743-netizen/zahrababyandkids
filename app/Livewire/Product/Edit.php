<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Category;
use App\Models\Owner;
use App\Models\Product;
use App\Models\ProductItem;

class Edit extends Component
{
    public Product $product;
    public $nama_produk;
    public $category_id;
    public $owner_id;
    
    public $items = [];

    // Bulk price target
    public $bulk_modal = 0;
    public $bulk_sell = 0;
    public $bulk_jual = 0;

    public function applyBulkPrice()
    {
        foreach($this->items as $k => $m) {
            $this->items[$k]['modal'] = $this->bulk_modal;
            $this->items[$k]['sell'] = $this->bulk_sell;
            $this->items[$k]['jual'] = $this->bulk_jual;
        }
    }

    public function mount(Product $product)
    {
        $product->load(['items.variantOption1', 'items.variantOption2']);
        $this->product = $product;
        
        $this->nama_produk = $product->nama_produk;
        $this->category_id = $product->category_id;
        $this->owner_id = $product->owner_id;

        foreach($product->items as $item) {
            $this->items[$item->id] = [
                'id' => $item->id,
                'v1' => $item->variantOption1 ? $item->variantOption1->value : 'Standard',
                'v2' => $item->variantOption2 ? $item->variantOption2->value : null,
                'modal' => $item->harga_modal,
                'sell' => $item->harga_sell,
                'jual' => $item->harga_jual,
                'stok' => $item->stok_akhir
            ];
        }
    }

    public function save()
    {
        $this->validate([
            'nama_produk' => 'required|string',
            'category_id' => 'required',
        ]);

        $this->product->update([
            'nama_produk' => $this->nama_produk,
            'category_id' => $this->category_id ?: null,
            'owner_id' => $this->owner_id ?: null,
        ]);

        foreach($this->items as $id => $data) {
            ProductItem::where('id', $id)->update([
                'harga_modal' => $data['modal'],
                'harga_sell'  => $data['sell'],
                'harga_jual'  => $data['jual'],
                'stok_akhir'  => $data['stok'],
            ]);
        }

        return redirect()->to('/products/'.$this->product->id);
    }

    public function render()
    {
        return view('livewire.product.edit', [
            'categories' => Category::all(),
            'owners' => Owner::all()
        ])->layout('layouts.app');
    }
}
