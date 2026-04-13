<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;

class Detail extends Component
{
    public Product $product;

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
