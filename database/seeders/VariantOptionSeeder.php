<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VariantOption;

class VariantOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            // Warna (attribute_id: 1)
            ['id' => 1, 'attribute_id' => 1, 'value' => 'Merah'],
            ['id' => 5, 'attribute_id' => 1, 'value' => 'Biru'],
            ['id' => 8, 'attribute_id' => 1, 'value' => 'Pink'],
            ['id' => 9, 'attribute_id' => 1, 'value' => 'Sage'],
            ['id' => 10, 'attribute_id' => 1, 'value' => 'Hitam'],
            ['id' => 11, 'attribute_id' => 1, 'value' => 'Putih'],
            ['id' => 12, 'attribute_id' => 1, 'value' => 'Coklat susu'],
            ['id' => 13, 'attribute_id' => 1, 'value' => 'Mahagoni'],
            ['id' => 14, 'attribute_id' => 1, 'value' => 'Burgundi'],
            ['id' => 15, 'attribute_id' => 1, 'value' => 'Ivory'],

            // Ukuran (attribute_id: 2)
            ['id' => 3, 'attribute_id' => 2, 'value' => 'M'],
            ['id' => 17, 'attribute_id' => 2, 'value' => 'L'],
            ['id' => 18, 'attribute_id' => 2, 'value' => 'XL'],
            ['id' => 19, 'attribute_id' => 2, 'value' => 'XXL'],
            ['id' => 20, 'attribute_id' => 2, 'value' => '3XL'],
            ['id' => 24, 'attribute_id' => 2, 'value' => '70'],
            ['id' => 31, 'attribute_id' => 2, 'value' => 'S'],
            ['id' => 58, 'attribute_id' => 2, 'value' => '4XL'],
            ['id' => 59, 'attribute_id' => 2, 'value' => '55/90'],
            ['id' => 60, 'attribute_id' => 2, 'value' => '60/100'],
            ['id' => 61, 'attribute_id' => 2, 'value' => '65/110'],
            ['id' => 62, 'attribute_id' => 2, 'value' => '70/120'],
            ['id' => 63, 'attribute_id' => 2, 'value' => '75/130'],
            ['id' => 64, 'attribute_id' => 2, 'value' => '80/140'],
            ['id' => 65, 'attribute_id' => 2, 'value' => '85/150'],
            ['id' => 66, 'attribute_id' => 2, 'value' => '90/160'],
            ['id' => 67, 'attribute_id' => 2, 'value' => '95/170'],
            ['id' => 68, 'attribute_id' => 2, 'value' => '90'],
            ['id' => 69, 'attribute_id' => 2, 'value' => '100'],
            ['id' => 70, 'attribute_id' => 2, 'value' => '110'],
            ['id' => 71, 'attribute_id' => 2, 'value' => '120'],
            ['id' => 72, 'attribute_id' => 2, 'value' => '130'],
            ['id' => 73, 'attribute_id' => 2, 'value' => '140'],
            ['id' => 74, 'attribute_id' => 2, 'value' => '150'],
            ['id' => 75, 'attribute_id' => 2, 'value' => '160'],
            ['id' => 76, 'attribute_id' => 2, 'value' => '170'],

            // Usia (attribute_id: 3)
            ['id' => 6, 'attribute_id' => 3, 'value' => '0 - 6 Bulan'],
            ['id' => 25, 'attribute_id' => 3, 'value' => '6 bulan - 1 tahun'],
            ['id' => 26, 'attribute_id' => 3, 'value' => '1 - 2 tahun'],
            ['id' => 27, 'attribute_id' => 3, 'value' => '1 tahun'],
            ['id' => 28, 'attribute_id' => 3, 'value' => '2 tahun'],
            ['id' => 29, 'attribute_id' => 3, 'value' => '3 tahun'],
            ['id' => 32, 'attribute_id' => 3, 'value' => '4 tahun'],
            ['id' => 33, 'attribute_id' => 3, 'value' => '5 tahun'],
            ['id' => 34, 'attribute_id' => 3, 'value' => '6 tahun'],
            ['id' => 35, 'attribute_id' => 3, 'value' => '7 tahun'],
            ['id' => 36, 'attribute_id' => 3, 'value' => '8 tahun'],
            ['id' => 37, 'attribute_id' => 3, 'value' => '9 tahun'],
            ['id' => 38, 'attribute_id' => 3, 'value' => '10 tahun'],
            ['id' => 39, 'attribute_id' => 3, 'value' => '11 tahun'],
            ['id' => 40, 'attribute_id' => 3, 'value' => '12 tahun'],
            ['id' => 41, 'attribute_id' => 3, 'value' => '13 tahun'],
            ['id' => 42, 'attribute_id' => 3, 'value' => '14 tahun'],
            ['id' => 43, 'attribute_id' => 3, 'value' => '15 tahun'],
            ['id' => 44, 'attribute_id' => 3, 'value' => 'Dewasa'],
            ['id' => 45, 'attribute_id' => 3, 'value' => '2-3 tahun'],
            ['id' => 46, 'attribute_id' => 3, 'value' => '3-4 tahun'],
            ['id' => 47, 'attribute_id' => 3, 'value' => '4-5 tahun'],
            ['id' => 48, 'attribute_id' => 3, 'value' => '5-6 tahun'],
            ['id' => 49, 'attribute_id' => 3, 'value' => '6-7 tahun'],
            ['id' => 50, 'attribute_id' => 3, 'value' => '7-8 tahun'],
            ['id' => 51, 'attribute_id' => 3, 'value' => '8-9 tahun'],
            ['id' => 52, 'attribute_id' => 3, 'value' => '9-10 tahun'],
            ['id' => 53, 'attribute_id' => 3, 'value' => '10-11 tahun'],
            ['id' => 54, 'attribute_id' => 3, 'value' => '11-12 tahun'],
            ['id' => 55, 'attribute_id' => 3, 'value' => '12-13 tahun'],
            ['id' => 56, 'attribute_id' => 3, 'value' => '13-14 tahun'],
            ['id' => 57, 'attribute_id' => 3, 'value' => '14-15 tahun'],
        ];

        foreach ($options as $option) {
            VariantOption::updateOrCreate(
                ['id' => $option['id']],
                [
                    'attribute_id' => $option['attribute_id'],
                    'value' => $option['value']
                ]
            );
        }
    }
}
