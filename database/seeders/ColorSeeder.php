<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            // Básicos
            ['name' => 'Blanco',        'hex_code' => '#FFFFFF'],
            ['name' => 'Negro',         'hex_code' => '#000000'],
            ['name' => 'Gris',          'hex_code' => '#808080'],
            ['name' => 'Gris Claro',    'hex_code' => '#D3D3D3'],
            ['name' => 'Gris Oscuro',   'hex_code' => '#404040'],
            // Beige / Crema
            ['name' => 'Beige',         'hex_code' => '#F5F0E8'],
            ['name' => 'Crema',         'hex_code' => '#FFFDD0'],
            ['name' => 'Camel',         'hex_code' => '#C19A6B'],
            ['name' => 'Marrón',        'hex_code' => '#795548'],
            // Azules
            ['name' => 'Azul',          'hex_code' => '#2196F3'],
            ['name' => 'Azul Marino',   'hex_code' => '#1A237E'],
            ['name' => 'Azul Cielo',    'hex_code' => '#87CEEB'],
            ['name' => 'Azul Oscuro',   'hex_code' => '#0D47A1'],
            ['name' => 'Jean Claro',    'hex_code' => '#7BAFD4'],
            ['name' => 'Jean Oscuro',   'hex_code' => '#2C4A6E'],
            // Rojos / Rosas
            ['name' => 'Rojo',          'hex_code' => '#F44336'],
            ['name' => 'Rojo Vino',     'hex_code' => '#7B1FA2'],
            ['name' => 'Rosa',          'hex_code' => '#E91E8C'],
            ['name' => 'Rosa Palo',     'hex_code' => '#F8BBD9'],
            ['name' => 'Coral',         'hex_code' => '#FF7F50'],
            // Verdes
            ['name' => 'Verde',         'hex_code' => '#4CAF50'],
            ['name' => 'Verde Militar', 'hex_code' => '#4B5320'],
            ['name' => 'Verde Menta',   'hex_code' => '#98FF98'],
            ['name' => 'Verde Botella', 'hex_code' => '#006400'],
            // Amarillos / Naranjas
            ['name' => 'Amarillo',      'hex_code' => '#FFEB3B'],
            ['name' => 'Mostaza',       'hex_code' => '#FFDB58'],
            ['name' => 'Naranja',       'hex_code' => '#FF9800'],
            // Morados
            ['name' => 'Morado',        'hex_code' => '#9C27B0'],
            ['name' => 'Lila',          'hex_code' => '#C8A2C8'],
            ['name' => 'Lavanda',       'hex_code' => '#E6E6FA'],
            // Especiales
            ['name' => 'Multicolor',    'hex_code' => '#FF6B6B'],
            ['name' => 'Estampado',     'hex_code' => '#A0A0A0'],
        ];

        foreach ($colors as $i => $color) {
            Color::updateOrCreate(
                ['name' => $color['name']],
                $color
            );
        }

        $this->command->info('✅ Colores creados: ' . count($colors));
    }
}
