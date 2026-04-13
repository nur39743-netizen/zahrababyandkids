<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;

class Detail extends Component
{
    public Category $category;

    public function mount($id)
    {
        $this->category = Category::with(['products' => function ($query) {
            $query->withCount('items')->latest();
        }])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.category.detail')->layout('layouts.app');
    }
}
