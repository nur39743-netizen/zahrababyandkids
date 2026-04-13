<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;

class Trashed extends Component
{
    public function restore($id)
    {
        $product = Product::withTrashed()->find($id);
        if ($product) {
            $product->restore();
            $product->items()->withTrashed()->restore();
            session()->flash('message', 'Produk berhasil dikembalikan.');
        }
    }

    public function forceDelete($id)
    {
        $product = Product::withTrashed()->find($id);
        if ($product) {
            // Hapus semua transaction_items terkait
            $product->items()->withTrashed()->each(function ($item) {
                $item->transactionItems()->delete();
            });
            $product->items()->withTrashed()->forceDelete();
            $product->forceDelete();
            session()->flash('message', 'Produk dan semua data terkait berhasil dihapus permanen.');
        }
    }

    public function render()
    {
        $trashedProducts = Product::onlyTrashed()->with(['category', 'owner', 'items' => function ($q) {
            $q->withTrashed();
        }])->get();

        return view('livewire.product.trashed', compact('trashedProducts'));
    }
}
