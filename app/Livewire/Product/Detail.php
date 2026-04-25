<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;

class Detail extends Component
{
    public Product $product;

    public function mount(Product $product): void
    {
        $this->product = Product::query()
            ->withTotalTerjual()
            ->with([
                'category',
                'owner',
                'supplier',
                'items.variantOption1',
                'items.variantOption2',
                'items' => function ($q) {
                    $q->withSum('transactionItems as terjual', 'qty');
                },
            ])
            ->findOrFail($product->id);
    }

    public function delete()
    {
        $this->product->delete(); // Soft delete
        return redirect('/products')->with('message', 'Produk berhasil dinonaktifkan.');
    }

    public function render()
    {
        return view('livewire.product.detail');
    }
}
