<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VariantAttribute;

class VariantAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            ['id' => 1, 'name' => 'Warna'],
            ['id' => 2, 'name' => 'Ukuran'],
            ['id' => 3, 'name' => 'Usia'],
        ];

        foreach ($attributes as $attribute) {
            VariantAttribute::updateOrCreate(
                ['id' => $attribute['id']],
                ['name' => $attribute['name']]
            );
        }
    }
}
