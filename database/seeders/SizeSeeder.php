<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Size;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            // Adulto — letras
            ['name' => 'XS',   'code' => 'XS',   'type' => 'adult',   'sort_order' => 1],
            ['name' => 'S',    'code' => 'S',    'type' => 'adult',   'sort_order' => 2],
            ['name' => 'M',    'code' => 'M',    'type' => 'adult',   'sort_order' => 3],
            ['name' => 'L',    'code' => 'L',    'type' => 'adult',   'sort_order' => 4],
            ['name' => 'XL',   'code' => 'XL',   'type' => 'adult',   'sort_order' => 5],
            ['name' => 'XXL',  'code' => 'XXL',  'type' => 'adult',   'sort_order' => 6],
            ['name' => 'XXXL', 'code' => 'XXXL', 'type' => 'adult',   'sort_order' => 7],
            // Adulto — pantalones (numérico)
            ['name' => '28',   'code' => 'W28',  'type' => 'numeric', 'sort_order' => 10],
            ['name' => '29',   'code' => 'W29',  'type' => 'numeric', 'sort_order' => 11],
            ['name' => '30',   'code' => 'W30',  'type' => 'numeric', 'sort_order' => 12],
            ['name' => '31',   'code' => 'W31',  'type' => 'numeric', 'sort_order' => 13],
            ['name' => '32',   'code' => 'W32',  'type' => 'numeric', 'sort_order' => 14],
            ['name' => '33',   'code' => 'W33',  'type' => 'numeric', 'sort_order' => 15],
            ['name' => '34',   'code' => 'W34',  'type' => 'numeric', 'sort_order' => 16],
            ['name' => '36',   'code' => 'W36',  'type' => 'numeric', 'sort_order' => 17],
            ['name' => '38',   'code' => 'W38',  'type' => 'numeric', 'sort_order' => 18],
            // Niños — tallas por edad/número
            ['name' => '0-3 meses',  'code' => 'K0-3M',  'type' => 'kids', 'sort_order' => 20],
            ['name' => '3-6 meses',  'code' => 'K3-6M',  'type' => 'kids', 'sort_order' => 21],
            ['name' => '6-12 meses', 'code' => 'K6-12M', 'type' => 'kids', 'sort_order' => 22],
            ['name' => '12-18 meses', 'code' => 'K12-18M', 'type' => 'kids', 'sort_order' => 23],
            ['name' => '18-24 meses', 'code' => 'K18-24M', 'type' => 'kids', 'sort_order' => 24],
            ['name' => 'Talla 2',    'code' => 'K2',      'type' => 'kids', 'sort_order' => 25],
            ['name' => 'Talla 3',    'code' => 'K3',      'type' => 'kids', 'sort_order' => 26],
            ['name' => 'Talla 4',    'code' => 'K4',      'type' => 'kids', 'sort_order' => 27],
            ['name' => 'Talla 5',    'code' => 'K5',      'type' => 'kids', 'sort_order' => 28],
            ['name' => 'Talla 6',    'code' => 'K6',      'type' => 'kids', 'sort_order' => 29],
            ['name' => 'Talla 8',    'code' => 'K8',      'type' => 'kids', 'sort_order' => 30],
            ['name' => 'Talla 10',   'code' => 'K10',     'type' => 'kids', 'sort_order' => 31],
            ['name' => 'Talla 12',   'code' => 'K12',     'type' => 'kids', 'sort_order' => 32],
            ['name' => 'Talla 14',   'code' => 'K14',     'type' => 'kids', 'sort_order' => 33],
            // Talla única
            ['name' => 'Talla Única', 'code' => 'ONE',     'type' => 'adult', 'sort_order' => 40],
        ];

        foreach ($sizes as $size) {
            Size::updateOrCreate(['code' => $size['code']], $size);
        }

        $this->command->info('✅ Tallas creadas: ' . count($sizes));
    }
}
