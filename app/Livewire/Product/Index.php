<?php

namespace App\Livewire\Product;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Owner;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedOwner = ''; // '' = semua, 'milik_sendiri' = toko, int = ID konsinyasi

    public ?string $previewUrl = null;

    public string $previewAlt = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function selectOwner($value)
    {
        $this->selectedOwner = $value;
        $this->resetPage();
    }

    public function openPreview(int $productId): void
    {
        $product = Product::query()->find($productId);
        if (! $product || ! $product->foto) {
            return;
        }

        $this->previewUrl = asset('storage/' . $product->foto);
        $this->previewAlt = $product->nama_produk;
    }

    public function closePreview(): void
    {
        $this->previewUrl = null;
        $this->previewAlt = '';
    }

    public function render()
    {
        $products = Product::with(['category', 'owner', 'supplier'])
            ->withTotalTerjual()
            ->withSum('items', 'stok_akhir')
            ->withMin('items', 'harga_jual')
            ->withMax('items', 'harga_jual')
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('nama_produk', 'like', '%'.$this->search.'%')
                      ->orWhere('kode_produk', 'like', '%'.$this->search.'%')
                      ->orWhereHas('owner', function($qo) {
                          $qo->where('nama_owner', 'like', '%'.$this->search.'%');
                      });
                });
            })
            ->when($this->selectedOwner !== '', function($query) {
                if ($this->selectedOwner === 'milik_sendiri') {
                    $query->whereNull('owner_id');
                } else {
                    $query->where('owner_id', $this->selectedOwner);
                }
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('livewire.product.index', [
            'products' => $products,
            'owners' => Owner::orderBy('nama_owner')->get()
        ]);
    }
}
