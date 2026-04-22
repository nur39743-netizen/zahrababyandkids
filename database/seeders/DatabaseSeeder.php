<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'super@admin.com'],
            [
                'name' => 'Mama Almahyra',
                'password' => bcrypt('password'),
                'role' => 'super_admin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'fahria@zbk.com'],
            [
                'name' => 'Fahria',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]
        );

         User::updateOrCreate(
            ['email' => 'marlina@zbk.com'],
            [
                'name' => 'Marlina',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]
        );

        

        $this->call([
            VariantAttributeSeeder::class,
            VariantOptionSeeder::class,
        ]);
    }
}
