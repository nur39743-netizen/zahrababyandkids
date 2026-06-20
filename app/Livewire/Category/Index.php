<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;

class Index extends Component
{
    public $nama_kategori;

    public function save()
    {
        $this->validate(['nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori']);
        Category::create([
            'nama_kategori' => $this->nama_kategori,
            'slug' => Str::slug($this->nama_kategori)
        ]);
        $this->reset('nama_kategori');
    }

    public function delete($id)
    {
        abort_if(auth()->user()->role === 'admin', 403, 'Admin tidak diizinkan menghapus data.');
        Category::find($id)->delete();
    }

    public function render()
    {
        return view('livewire.category.index', [
            'categories' => Category::withCount('products')->orderBy('nama_kategori')->get()
        ]);
    }
}
