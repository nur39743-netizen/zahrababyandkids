<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\ProductCodeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalculateProductCodes extends Command
{
    protected $signature = 'products:recalculate-codes
                            {--dry-run : Hanya tampilkan perubahan tanpa menyimpan}
                            {--force : Jalankan tanpa konfirmasi}';

    protected $description = 'Set ulang kode_produk semua produk (berkategori) ke format singkatan kategori + nomor urut';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $products = Product::query()
            ->with(['category' => fn ($q) => $q->withTrashed()])
            ->whereNotNull('category_id')
            ->orderBy('id')
            ->get();

        $skipped = [];
        $byPrefix = [];

        foreach ($products as $product) {
            if (! $product->category) {
                $skipped[] = [$product->id, $product->nama_produk, 'Kategori tidak ditemukan (mungkin dihapus permanen)'];

                continue;
            }

            $prefix = ProductCodeService::prefixForCategory($product->category);
            $byPrefix[$prefix][] = $product;
        }

        $updates = [];
        foreach ($byPrefix as $prefix => $items) {
            $n = 1;
            foreach ($items as $product) {
                $newCode = $prefix . '-' . str_pad((string) $n, 3, '0', STR_PAD_LEFT);
                if ((string) $product->kode_produk !== $newCode) {
                    $updates[] = [$product, $newCode];
                }
                $n++;
            }
        }

        if ($skipped !== []) {
            $this->warn('Produk tanpa kategori valid yang bisa dipakai (' . count($skipped) . '):');
            foreach (array_slice($skipped, 0, 10) as $row) {
                $this->line("  ID {$row[0]} — {$row[1]} — {$row[2]}");
            }
            if (count($skipped) > 10) {
                $this->line('  …');
            }
            $this->newLine();
        }

        if ($updates === []) {
            $this->info('Tidak ada perubahan: semua kode sudah sesuai format, atau tidak ada produk berkategori.');

            return self::SUCCESS;
        }

        $this->table(['ID', 'Nama', 'Kode lama', 'Kode baru'], array_map(function ($row) {
            /** @var Product $p */
            $p = $row[0];

            return [$p->id, \Illuminate\Support\Str::limit($p->nama_produk, 40), $p->kode_produk, $row[1]];
        }, $updates));

        if ($dryRun) {
            $this->info('Dry run: tidak ada data yang diubah. Jalankan tanpa --dry-run untuk menyimpan.');

            return self::SUCCESS;
        }

        if (! $this->option('force') && ! $this->confirm('Simpan ' . count($updates) . ' perubahan kode_produk?', true)) {
            $this->warn('Dibatalkan.');

            return self::FAILURE;
        }

        DB::transaction(function () use ($updates) {
            foreach ($updates as [$product, $newCode]) {
                Product::whereKey($product->id)->update(['kode_produk' => $newCode]);
            }
        });

        $this->info('Selesai: ' . count($updates) . ' kode produk diperbarui.');

        return self::SUCCESS;
    }
}
