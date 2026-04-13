<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;

class Trashed extends Component
{
    public function restore($id)
    {
        $category = Category::withTrashed()->find($id);
        if ($category) {
            $category->restore();
            $category->products()->withTrashed()->restore();
            session()->flash('message', 'Kategori berhasil dikembalikan.');
        }
    }

    public function forceDelete($id)
    {
        $category = Category::withTrashed()->find($id);
        if ($category) {
            $category->products()->withTrashed()->each(function ($product) {
                $product->items()->withTrashed()->forceDelete();
                $product->forceDelete();
            });
            $category->forceDelete();
            session()->flash('message', 'Kategori dan semua produk terkait berhasil dihapus permanen.');
        }
    }

    public function render()
    {
        $categories = Category::onlyTrashed()->withCount('products')->orderBy('deleted_at', 'desc')->get();
        return view('livewire.category.trashed', compact('categories'));
    }
}
