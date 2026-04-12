<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;

class Edit extends Component
{
    public Category $category;
    public $nama_kategori;

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->nama_kategori = $category->nama_kategori;
    }

    public function save()
    {
        $this->validate([
            'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori,' . $this->category->id,
        ]);

        $this->category->update([
            'nama_kategori' => $this->nama_kategori,
            'slug' => Str::slug($this->nama_kategori)
        ]);

        return redirect()->to('/categories');
    }

    public function render()
    {
        return view('livewire.category.edit')->layout('layouts.app');
    }
}
