<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;

class Detail extends Component
{
    public Product $product;

    public function mount(Product $product)
    {
        $product->load(['category', 'owner', 'items.variantOption1', 'items.variantOption2']);
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.product.detail');
    }
}
