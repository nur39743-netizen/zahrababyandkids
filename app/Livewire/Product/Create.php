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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
// Note: avoid strict TemporaryUploadedFile type-hint to be compatible with various Livewire versions

class Create extends Component
{
    use WithFileUploads;

    public $nama_produk = '';
    public $category_id = '';
    public $owner_id = '';
    public $supplier_id = '';
    public $gender = 'unisex';
    public $bahan = '';
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
        if (!$this->has_variant) {
            return;
        }

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
                        'stok' => 0,
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
                    'stok' => 0,
                ];
            }
        }
    }

    public function save()
    {
        $rules = [
            'nama_produk' => [
                'required',
                'string',
                \Illuminate\Validation\Rule::unique('products', 'nama_produk')->whereNull('deleted_at')
            ],
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

        // Pre-check upload size against server PHP limits to avoid hard failures
        $serverLimit = min($this->iniSizeToBytes(ini_get('upload_max_filesize')), $this->iniSizeToBytes(ini_get('post_max_size')));
        if ($this->foto && method_exists($this->foto, 'getSize') && $this->foto->getSize() > $serverLimit) {
            $this->addError('foto', 'Ukuran file melebihi batas server (' . $this->bytesToHuman($serverLimit) . ').');
            return;
        }
        if (!empty($this->item_fotos)) {
            foreach ($this->item_fotos as $k => $f) {
                if ($f && method_exists($f, 'getSize') && $f->getSize() > $serverLimit) {
                    $this->addError('item_fotos.' . $k, 'Ukuran file item melebihi batas server (' . $this->bytesToHuman($serverLimit) . ').');
                    return;
                }
            }
        }

        $this->validate($rules, $messages, $attributes);

        DB::beginTransaction();
        try {
            $productFotoPath = null;

            if ($this->foto && method_exists($this->foto, 'getRealPath')) {
                $productFotoPath = $this->storeImageAsWebp($this->foto, 'products');
            }

            $category = Category::find($this->category_id);
            $kodeProduk = $category
                ? ProductCodeService::nextCodeForCategory($category)
                : ('PRD' . date('ym') . strtoupper(Str::random(4)));

            $product = Product::create([
                'nama_produk' => $this->nama_produk,
                'slug' => Str::slug($this->nama_produk . '-' . rand(100, 999)),
                'kode_produk' => $kodeProduk,
                'category_id' => $this->category_id ?: null,
                'owner_id' => $this->owner_id ?: null,
                'supplier_id' => $this->supplier_id ?: null,
                'gender' => $this->gender,
                'bahan' => $this->bahan ?: null,
                'foto' => $productFotoPath,
            ]);

            if ($this->has_variant && count($this->matrix) > 0) {
                foreach ($this->matrix as $index => $m) {
                    $itemFotoPath = null;
                    if (isset($this->item_fotos[$index]) && $this->item_fotos[$index] && method_exists($this->item_fotos[$index], 'getRealPath')) {
                        $itemFotoPath = $this->storeImageAsWebp($this->item_fotos[$index], 'product_items');
                    }

                    ProductItem::create([
                        'product_id' => $product->id,
                        'variant_option_1_id' => $m['v1_id'] ?: null,
                        'variant_option_2_id' => $m['v2_id'] ?: null,
                        'harga_modal' => $m['modal'],
                        'harga_sell' => $m['sell'],
                        'harga_jual' => $m['jual'],
                        'stok_akhir' => $m['stok'],
                        'foto' => $itemFotoPath,
                    ]);
                }
            } else {
                $itemFotoPath = null;
                if (isset($this->item_fotos[0]) && $this->item_fotos[0] && method_exists($this->item_fotos[0], 'getRealPath')) {
                    $itemFotoPath = $this->storeImageAsWebp($this->item_fotos[0], 'product_items');
                }

                ProductItem::create([
                    'product_id' => $product->id,
                    'harga_modal' => $this->harga_modal,
                    'harga_sell' => $this->harga_sell,
                    'harga_jual' => $this->harga_jual,
                    'stok_akhir' => $this->stok_akhir,
                    'foto' => $itemFotoPath,
                ]);
            }

            DB::commit();

            if ($product->category_id) {
                $cat = Category::find($product->category_id);
                $cat->total_produk = Product::where('category_id', $cat->id)->count();
                $cat->save();
            }

            session()->flash('success', 'Produk berhasil disimpan.');

            return redirect()->to('/products');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Gagal menyimpan produk: ' . $e->getMessage() . ' di baris ' . $e->getLine());
            $errMsg = 'Terjadi kesalahan: ' . $e->getMessage();
            session()->flash('error', $errMsg);
        }
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
            // If decoding fails, store original upload with extension fallback
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

        // Prefer WebP when supported
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

        // Fallback to JPEG
        if (function_exists('imagejpeg')) {
            $filename = $folder . '/' . Str::random(40) . '.jpg';
            ob_start();
            imagejpeg($image, null, 85);
            $out = ob_get_clean();
            imagedestroy($image);
            Storage::disk('public')->put($filename, $out);
            return $filename;
        }

        // Fallback to PNG
        if (function_exists('imagepng')) {
            $filename = $folder . '/' . Str::random(40) . '.png';
            ob_start();
            imagepng($image);
            $out = ob_get_clean();
            imagedestroy($image);
            Storage::disk('public')->put($filename, $out);
            return $filename;
        }

        // Last resort: save original binary
        imagedestroy($image);
        $origName = method_exists($file, 'getClientOriginalName') ? $file->getClientOriginalName() : $file->getFilename();
        $ext = pathinfo($origName, PATHINFO_EXTENSION) ?: 'bin';
        $filename = $folder . '/' . Str::random(40) . '.' . $ext;
        Storage::disk('public')->put($filename, $binary);
        return $filename;
    }

    private function iniSizeToBytes($size)
    {
        $size = trim($size);
        if ($size === '') return PHP_INT_MAX;
        $unit = strtolower(substr($size, -1));
        $value = (float) rtrim($size, 'bkmgtpezyBKMGTP');
        switch ($unit) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        return (int) $value;
    }

    private function bytesToHuman($bytes)
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        }
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    public function render()
    {
        return view('livewire.product.create', [
            'categories' => Category::all(),
            'owners' => Owner::all(),
            'suppliers' => \App\Models\Supplier::orderBy('name')->get(),
            'variant_attributes' => VariantAttribute::with('options')->get(),
        ])->layout('layouts.app');
    }
}
