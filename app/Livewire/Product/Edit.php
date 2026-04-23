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
use App\Services\ProductCodeService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Edit extends Component
{
    use WithFileUploads;

    public Product $product;
    public $nama_produk;
    public $category_id;
    public $owner_id;
    public $supplier_id;
    public $gender = 'unisex';
    public $bahan = '';
    public $foto;

    public $items = [];
    public $item_fotos = []; // Array of uploaded files for each item

    // Bulk price target
    public $bulk_modal = 0;
    public $bulk_sell = 0;
    public $bulk_jual = 0;

    // Modal for adding item
    public $showAddItemModal = false;
    public $selectedVariant1 = '';
    public $selectedVariant2 = '';
    public $variant_attributes = [];

    public function applyBulkPrice()
    {
        foreach ($this->items as $k => $m) {
            $this->items[$k]['modal'] = $this->bulk_modal;
            $this->items[$k]['sell'] = $this->bulk_sell;
            $this->items[$k]['jual'] = $this->bulk_jual;
        }
    }

    public function addItem()
    {
        $this->showAddItemModal = true;
        $this->selectedVariant1 = '';
        $this->selectedVariant2 = '';
    }

    public function addItemFromModal()
    {
        $this->validate([
            'selectedVariant1' => 'required',
        ]);

        // Check if combination already exists
        $existing = collect($this->items)->first(function ($item) {
            return $item['v1'] == $this->selectedVariant1 && $item['v2'] == $this->selectedVariant2;
        });

        if ($existing) {
            $this->addError('selectedVariant1', 'Kombinasi varian ini sudah ada.');
            return;
        }

        $this->items[] = [
            'id' => 'new_' . uniqid(),
            'v1' => $this->selectedVariant1,
            'v2' => $this->selectedVariant2 ?: null,
            'modal' => 0,
            'sell' => 0,
            'jual' => 0,
            'stok' => 0
        ];
        $this->item_fotos[] = null;

        $this->showAddItemModal = false;
        $this->selectedVariant1 = '';
        $this->selectedVariant2 = '';
    }

    public function deleteItem($index)
    {
        $data = $this->items[$index];
        if (is_numeric($data['id'])) {
            ProductItem::find($data['id'])->delete();
        }
        unset($this->items[$index]);
        unset($this->item_fotos[$index]);
        $this->items = array_values($this->items);
        $this->item_fotos = array_values($this->item_fotos);
    }

    public function mount(Product $product)
    {
        $product->load(['items.variantOption1.attribute', 'items.variantOption2.attribute']);
        $this->product = $product;

        $this->nama_produk = $product->nama_produk;
        $this->category_id = $product->category_id;
        $this->owner_id = $product->owner_id;
        $this->supplier_id = $product->supplier_id;
        $this->gender = $product->gender ?: 'unisex';
        $this->bahan = $product->bahan ?: '';
        $this->foto = $product->foto;

        $attributeIds = [];
        foreach ($product->items as $item) {
            if ($item->variantOption1) {
                $attributeIds[] = $item->variantOption1->attribute_id;
            }
            if ($item->variantOption2) {
                $attributeIds[] = $item->variantOption2->attribute_id;
            }
        }
        $attributeIds = array_unique($attributeIds);
        $this->variant_attributes = VariantAttribute::with('options')->whereIn('id', $attributeIds)->get();

        foreach ($product->items as $item) {
            $this->items[] = [
                'id' => $item->id,
                'v1' => $item->variantOption1 ? $item->variantOption1->value : 'Standard',
                'v2' => $item->variantOption2 ? $item->variantOption2->value : null,
                'modal' => $item->harga_modal,
                'sell' => $item->harga_sell,
                'jual' => $item->harga_jual,
                'stok' => $item->stok_akhir
            ];
            $this->item_fotos[] = $item->foto;
        }
    }

    public function save()
    {
        $this->validate([
            'nama_produk' => 'required|string',
            'category_id' => 'required',
            'gender' => 'required|in:male,female,unisex',
            'bahan' => 'nullable|string|max:255',
        ]);
        
        $fotoPath = $this->product->foto;
        if ($this->foto instanceof TemporaryUploadedFile) {
            $fotoPath = $this->storeImageAsWebp($this->foto, 'products');
        }

        $categoryChanged = (string) ($this->category_id ?: '') !== (string) ($this->product->category_id ?: '');
        $payload = [
            'nama_produk' => $this->nama_produk,
            'category_id' => $this->category_id ?: null,
            'owner_id' => $this->owner_id ?: null,
            'supplier_id' => $this->supplier_id ?: null,
            'gender' => $this->gender,
            'bahan' => $this->bahan ?: null,
            'foto' => $fotoPath,
        ];

        if ($categoryChanged && $this->category_id) {
            $cat = Category::find($this->category_id);
            if ($cat) {
                $payload['kode_produk'] = ProductCodeService::nextCodeForCategory($cat);
            }
        }

        $this->product->update($payload);

        foreach ($this->items as $index => $data) {
            $fotoPath = null;
            if (isset($this->item_fotos[$index]) && $this->item_fotos[$index] instanceof TemporaryUploadedFile) {
                $fotoPath = $this->storeImageAsWebp($this->item_fotos[$index], 'product_items');
            } elseif (isset($this->item_fotos[$index])) {
                $fotoPath = $this->item_fotos[$index];
            }

            if (is_numeric($data['id'])) {
                // Update existing
                ProductItem::where('id', $data['id'])->update([
                    'harga_modal' => $data['modal'],
                    'harga_sell' => $data['sell'],
                    'harga_jual' => $data['jual'],
                    'stok_akhir' => $data['stok'],
                    'foto' => $fotoPath,
                ]);
            } else {
                // Create new
                $variant1Id = null;
                $variant2Id = null;
                if ($data['v1'] !== 'Standard') {
                    $option1 = VariantOption::where('value', $data['v1'])->first();
                    $variant1Id = $option1 ? $option1->id : null;
                }
                if ($data['v2']) {
                    $option2 = VariantOption::where('value', $data['v2'])->first();
                    $variant2Id = $option2 ? $option2->id : null;
                }
                ProductItem::create([
                    'product_id' => $this->product->id,
                    'variant_option_1_id' => $variant1Id,
                    'variant_option_2_id' => $variant2Id,
                    'harga_modal' => $data['modal'],
                    'harga_sell' => $data['sell'],
                    'harga_jual' => $data['jual'],
                    'stok_akhir' => $data['stok'],
                    'foto' => $fotoPath,
                ]);
            }
        }

        return redirect()->to('/products/' . $this->product->id);
    }

    private function storeImageAsWebp(TemporaryUploadedFile $file, string $folder): string
    {
        if (!function_exists('imagecreatefromstring') || !function_exists('imagewebp')) {
            throw new \RuntimeException('GD extension with WebP support is not available.');
        }

        $binary = file_get_contents($file->getRealPath());
        if ($binary === false) {
            throw new \RuntimeException('Failed to read uploaded image file.');
        }

        $image = imagecreatefromstring($binary);
        if ($image === false) {
            throw new \RuntimeException('Invalid image file.');
        }

        $filename = $folder . '/' . Str::random(40) . '.webp';
        ob_start();
        imagewebp($image, null, 80);
        $webpBinary = ob_get_clean();
        imagedestroy($image);

        if ($webpBinary === false) {
            throw new \RuntimeException('Failed to encode image as WebP.');
        }

        Storage::disk('public')->put($filename, $webpBinary);

        return $filename;
    }

    public function render()
    {
        return view('livewire.product.edit', [
            'categories' => Category::all(),
            'owners' => Owner::all(),
            'suppliers' => \App\Models\Supplier::orderBy('name')->get(),
            'variant_attributes' => $this->variant_attributes
        ]);
    }
}
