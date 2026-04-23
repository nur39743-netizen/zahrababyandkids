<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;

class ProductCodeService
{
    /**
     * Build a 3-character (or similar) prefix from category name, e.g.
     * "Setelan Harian" → STH, "One set" → OST, "2 in 1" → 2I1.
     */
    public static function prefixFromCategoryName(string $namaKategori): string
    {
        $normalized = trim(preg_replace('/\s+/u', ' ', $namaKategori));

        if (preg_match('/^(\d+)\s+in\s+(\d+)$/iu', $normalized, $m)) {
            return strtoupper($m[1] . 'I' . $m[2]);
        }

        $words = preg_split('/\s+/u', $normalized, -1, PREG_SPLIT_NO_EMPTY);
        if (count($words) >= 3) {
            $a = mb_strtoupper(mb_substr($words[0], 0, 1));
            $b = mb_strtoupper(mb_substr($words[1], 0, 1));
            $c = mb_strtoupper(mb_substr($words[2], 0, 1));

            return $a . $b . $c;
        }

        if (count($words) >= 2) {
            $w1 = $words[0];
            $w2 = $words[1];
            $len1 = mb_strlen($w1);
            $len2 = mb_strlen($w2);

            if ($len1 > 3) {
                return mb_strtoupper(
                    mb_substr($w1, 0, 1) .
                    mb_substr($w1, 2, 1) .
                    mb_substr($w2, 0, 1)
                );
            }

            $a = mb_strtoupper(mb_substr($w1, 0, 1));
            $b = mb_strtoupper(mb_substr($w2, 0, 1));
            $c = $len2 >= 3
                ? mb_strtoupper(mb_substr($w2, 2, 1))
                : mb_strtoupper(mb_substr($w2, -1, 1));

            return $a . $b . $c;
        }

        $clean = preg_replace('/[^\p{L}\p{N}]+/u', '', $normalized);
        $clean = mb_strtoupper($clean);
        if (mb_strlen($clean) >= 3) {
            return mb_substr($clean, 0, 3);
        }

        return str_pad($clean !== '' ? $clean : 'PRD', 3, 'X');
    }

    public static function prefixForCategory(Category $category): string
    {
        return self::prefixFromCategoryName($category->nama_kategori);
    }

    /**
     * Next code like PREFIX-001; suffix is global for that prefix (unique across categories).
     */
    public static function nextCodeForCategory(Category $category): string
    {
        $prefix = self::prefixForCategory($category);
        $regex = '/^' . preg_quote($prefix, '/') . '-(\d+)$/i';

        $max = 0;
        foreach (Product::query()->pluck('kode_produk') as $code) {
            if ($code && preg_match($regex, (string) $code, $m)) {
                $max = max($max, (int) $m[1]);
            }
        }

        return $prefix . '-' . str_pad((string) ($max + 1), 3, '0', STR_PAD_LEFT);
    }
}
