<?php

namespace App\Livewire\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\Owner;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\VariantAttribute;
use App\Models\VariantOption;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
    use WithFileUploads;

    public $nama_produk = '';
    public $category_id = '';
    public $owner_id = '';
    public $foto;

    public $has_variant = false;

    // Non variant
    public $harga_modal = 0;
    public $harga_sell = 0; // grosir
    public $harga_jual = 0; // ecer
    public $stok_akhir = 0;

    // Variants
    public $variant1_id = '';
    public $variant1_options = []; // Array checkboxes

    public $variant2_id = '';
    public $variant2_options = []; // Array checkboxes

    public $matrix = [];
    public $item_fotos = []; // Array of uploaded files for each matrix item
    // Bulk price target
    public $bulk_modal = 0;
    public $bulk_sell = 0;
    public $bulk_jual = 0;

    public function updated($propertyName)
    {
        if ($propertyName === 'variant1_id' && $this->variant1_id == $this->variant2_id) {
            $this->variant2_id = '';
            $this->variant2_options = [];
        }

        if ($propertyName === 'variant2_id' && $this->variant2_id == $this->variant1_id) {
            $this->variant2_id = '';
            $this->variant2_options = [];
        }

        if (
            in_array($propertyName, ['variant1_id', 'variant2_id', 'has_variant']) ||
            str_starts_with($propertyName, 'variant1_options') ||
            str_starts_with($propertyName, 'variant2_options')
        ) {
            $this->generateMatrix();
        }
    }

    public function applyBulkPrice()
    {
        foreach ($this->matrix as $k => $m) {
            $this->matrix[$k]['modal'] = $this->bulk_modal;
            $this->matrix[$k]['sell'] = $this->bulk_sell;
            $this->matrix[$k]['jual'] = $this->bulk_jual;
        }
    }

    public function deleteItem($index)
    {
        unset($this->matrix[$index]);
        unset($this->item_fotos[$index]);
        $this->matrix = array_values($this->matrix); // Reindex array
    }

    public function generateMatrix()
    {
        $this->matrix = [];
        if (!$this->has_variant) return;

        $opts1 = array_keys(array_filter($this->variant1_options));
        $opts2 = array_keys(array_filter($this->variant2_options));

        $v1s = VariantOption::whereIn('id', $opts1)->get()->keyBy('id');
        $v2s = VariantOption::whereIn('id', $opts2)->get()->keyBy('id');

        if (count($opts1) > 0 && count($opts2) > 0) {
            foreach ($opts1 as $o1) {
                foreach ($opts2 as $o2) {
                    $this->matrix[] = [
                        'v1_id' => $o1,
                        'v1_val' => $v1s[$o1]->value,
                        'v2_id' => $o2,
                        'v2_val' => $v2s[$o2]->value,
                        'modal' => 0,
                        'sell' => 0,
                        'jual' => 0,
                        'stok' => 0
                    ];
                }
            }
        } elseif (count($opts1) > 0) {
            foreach ($opts1 as $o1) {
                $this->matrix[] = [
                    'v1_id' => $o1,
                    'v1_val' => $v1s[$o1]->value,
                    'v2_id' => null,
                    'v2_val' => null,
                    'modal' => 0,
                    'sell' => 0,
                    'jual' => 0,
                    'stok' => 0
                ];
            }
        }
    }

    public function save()
    {
        $this->validate([
            'nama_produk' => 'required|string',
            'category_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $fotoPath = null;
            if ($this->foto) {
                $fotoPath = $this->foto->store('products', 'public');
            }

            $product = Product::create([
                'nama_produk' => $this->nama_produk,
                'slug' => Str::slug($this->nama_produk . '-' . rand(100, 999)),
                'kode_produk' => 'PRD' . date('ym') . strtoupper(Str::random(4)),
                'category_id' => $this->category_id ?: null,
                'owner_id' => $this->owner_id ?: null,
                'foto' => $fotoPath,
            ]);

            if ($this->has_variant && count($this->matrix) > 0) {
                foreach ($this->matrix as $index => $m) {
                    $fotoPath = isset($this->item_fotos[$index]) ? $this->item_fotos[$index]->store('product_items', 'public') : null;
                    ProductItem::create([
                        'product_id' => $product->id,
                        'variant_option_1_id' => $m['v1_id'] ?: null,
                        'variant_option_2_id' => $m['v2_id'] ?: null,
                        'harga_modal' => $m['modal'],
                        'harga_sell' => $m['sell'],
                        'harga_jual' => $m['jual'],
                        'stok_akhir' => $m['stok'],
                        'foto' => $fotoPath,
                    ]);
                }
            } else {
                $fotoPath = isset($this->item_fotos[0]) ? $this->item_fotos[0]->store('product_items', 'public') : null;
                ProductItem::create([
                    'product_id' => $product->id,
                    'harga_modal' => $this->harga_modal,
                    'harga_sell' => $this->harga_sell,
                    'harga_jual' => $this->harga_jual,
                    'stok_akhir' => $this->stok_akhir,
                    'foto' => $fotoPath,
                ]);
            }
            DB::commit();

            // update counter
            if ($product->category_id) {
                $cat = Category::find($product->category_id);
                $cat->total_produk = Product::where('category_id', $cat->id)->count();
                $cat->save();
            }

            return redirect()->to('/products');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.product.create', [
            'categories' => Category::all(),
            'owners' => Owner::all(),
            'variant_attributes' => VariantAttribute::with('options')->get()
        ])->layout('layouts.app');
    }
}
