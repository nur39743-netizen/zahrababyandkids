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
// avoid strict TemporaryUploadedFile type-hint for compatibility

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
        $rules = [
            'nama_produk' => 'required|string|unique:products,nama_produk,' . $this->product->id,
            'category_id' => 'required',
            'gender' => 'required|in:male,female,unisex',
            'bahan' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:51200',
            'item_fotos.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:51200',
        ];

        $messages = [
            'nama_produk.required' => 'Nama produk wajib diisi.',
            'nama_produk.string' => 'Nama produk tidak valid.',
            'nama_produk.unique' => 'Nama produk sudah digunakan.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'gender.required' => 'Jenis kelamin produk wajib dipilih.',
            'gender.in' => 'Pilihan gender tidak valid.',
            'bahan.max' => 'Panjang teks bahan maksimal :max karakter.',
            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus: jpg, jpeg, png, gif, webp.',
            'foto.max' => 'Ukuran foto maksimal :max KB.',
            'foto.uploaded' => 'Upload foto gagal. Pastikan ukuran file dan koneksi upload valid.',
            'item_fotos.*.image' => 'File foto item harus berupa gambar.',
            'item_fotos.*.mimes' => 'Format foto item harus: jpg, jpeg, png, gif, webp.',
            'item_fotos.*.max' => 'Ukuran foto item maksimal :max KB.',
            'item_fotos.*.uploaded' => 'Upload foto item gagal. Pastikan ukuran file dan koneksi upload valid.',
        ];

        $attributes = [
            'nama_produk' => 'Nama produk',
            'category_id' => 'Kategori',
            'foto' => 'Foto produk',
            'item_fotos.*' => 'Foto item',
        ];

        $this->validate($rules, $messages, $attributes);

        $fotoPath = $this->product->foto;
        if ($this->foto && method_exists($this->foto, 'getRealPath')) {
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
            if (isset($this->item_fotos[$index]) && $this->item_fotos[$index] && method_exists($this->item_fotos[$index], 'getRealPath')) {
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

        session()->flash('success', 'Perubahan produk berhasil disimpan.');

        return redirect()->to('/products/' . $this->product->id);
    }

    private function storeImageAsWebp($file, string $folder): string
    {
        $realPath = $file->getRealPath();
        $binary = @file_get_contents($realPath);
        if ($binary === false) {
            throw new \RuntimeException('Failed to read uploaded image file.');
        }

        $image = @imagecreatefromstring($binary);
        if ($image === false) {
            $origName = method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : $file->getFilename();
            $ext = pathinfo($origName, PATHINFO_EXTENSION) ?: 'bin';
            $filename = $folder . '/' . Str::random(40) . '.' . $ext;
            Storage::disk('public')->put($filename, $binary);
            return $filename;
        }

        // Resize image if it exceeds 1024x1024
        $maxWidth = 1024;
        $maxHeight = 1024;
        $origWidth = imagesx($image);
        $origHeight = imagesy($image);

        if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
            $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
            $newWidth = (int) ($origWidth * $ratio);
            $newHeight = (int) ($origHeight * $ratio);

            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);

            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
            imagedestroy($image);
            $image = $newImage;
        }

        if (function_exists('imagewebp')) {
            $filename = $folder . '/' . Str::random(40) . '.webp';
            ob_start();
            imagewebp($image, null, 80);
            $out = ob_get_clean();
            imagedestroy($image);
            if ($out === false) {
                throw new \RuntimeException('Failed to encode image as WebP.');
            }
            Storage::disk('public')->put($filename, $out);
            return $filename;
        }

        if (function_exists('imagejpeg')) {
            $filename = $folder . '/' . Str::random(40) . '.jpg';
            ob_start();
            imagejpeg($image, null, 85);
            $out = ob_get_clean();
            imagedestroy($image);
            Storage::disk('public')->put($filename, $out);
            return $filename;
        }

        if (function_exists('imagepng')) {
            $filename = $folder . '/' . Str::random(40) . '.png';
            ob_start();
            imagepng($image);
            $out = ob_get_clean();
            imagedestroy($image);
            Storage::disk('public')->put($filename, $out);
            return $filename;
        }

        imagedestroy($image);
        $origName = method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : $file->getFilename();
        $ext = pathinfo($origName, PATHINFO_EXTENSION) ?: 'bin';
        $filename = $folder . '/' . Str::random(40) . '.' . $ext;
        Storage::disk('public')->put($filename, $binary);
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
