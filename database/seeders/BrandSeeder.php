<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            // Marcas peruanas
            ['name' => 'Topy Top'],
            ['name' => 'Gamarra Style'],
            ['name' => 'Perú Moda'],
            ['name' => 'Studio F'],
            ['name' => 'Basement'],
            // Marcas internacionales comunes en Perú
            ['name' => 'Adidas'],
            ['name' => 'Nike'],
            ['name' => 'Puma'],
            ['name' => 'Levi\'s'],
            ['name' => 'H&M'],
            ['name' => 'Zara'],
            ['name' => 'Tommy Hilfiger'],
            ['name' => 'Calvin Klein'],
            ['name' => 'Polo Ralph Lauren'],
            // Genérica
            ['name' => 'Sin Marca'],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($brand['name'])],
                array_merge($brand, [
                    'slug'      => Str::slug($brand['name']),
                    'is_active' => true,
                ])
            );
        }

        $this->command->info('✅ Marcas creadas: ' . count($brands));
    }
}
