<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tree = [
            [
                'name'             => 'Hombre',
                'slug'             => 'hombre',
                'description'      => 'Ropa para hombre',
                'image'            => null,
                'meta_title'       => 'Ropa para Hombre | EM Collective',
                'meta_description' => 'Encuentra polos, camisas, pantalones y más para hombre. Envío a todo el Perú.',
                'sort_order'       => 1,
                'children' => [
                    ['name' => 'Polos',           'sort_order' => 1],
                    ['name' => 'Camisas',          'sort_order' => 2],
                    ['name' => 'Pantalones',        'sort_order' => 3],
                    ['name' => 'Shorts y Bermudas', 'sort_order' => 4],
                    ['name' => 'Casacas y Abrigos', 'sort_order' => 5],
                    ['name' => 'Hoodies y Poleras', 'sort_order' => 6],
                    ['name' => 'Ropa Deportiva',    'sort_order' => 7],
                    ['name' => 'Ropa Interior',     'sort_order' => 8],
                    ['name' => 'Pijamas',           'sort_order' => 9],
                    ['name' => 'Accesorios',        'sort_order' => 10],
                ],
            ],
            [
                'name'             => 'Mujer',
                'slug'             => 'mujer',
                'description'      => 'Ropa para mujer',
                'image'            => null,
                'meta_title'       => 'Ropa para Mujer | EM Collective',
                'meta_description' => 'Descubre vestidos, blusas, pantalones y más para mujer. Moda al mejor precio.',
                'sort_order'       => 2,
                'children' => [
                    ['name' => 'Vestidos',           'sort_order' => 1],
                    ['name' => 'Blusas y Tops',      'sort_order' => 2],
                    ['name' => 'Pantalones y Jeans',  'sort_order' => 3],
                    ['name' => 'Faldas',              'sort_order' => 4],
                    ['name' => 'Casacas y Abrigos',   'sort_order' => 5],
                    ['name' => 'Hoodies y Poleras',   'sort_order' => 6],
                    ['name' => 'Ropa Deportiva',      'sort_order' => 7],
                    ['name' => 'Ropa Interior',       'sort_order' => 8],
                    ['name' => 'Pijamas y Loungewear', 'sort_order' => 9],
                    ['name' => 'Accesorios',          'sort_order' => 10],
                ],
            ],
            [
                'name'             => 'Niños',
                'slug'             => 'ninos',
                'description'      => 'Ropa para niños y niñas',
                'image'            => null,
                'meta_title'       => 'Ropa para Niños | EM Collective',
                'meta_description' => 'Ropa cómoda y colorida para niños y niñas de todas las edades. Envío a todo el Perú.',
                'sort_order'       => 3,
                'children' => [
                    ['name' => 'Bebé (0-2 años)',     'sort_order' => 1],
                    ['name' => 'Niño (3-8 años)',      'sort_order' => 2],
                    ['name' => 'Niña (3-8 años)',      'sort_order' => 3],
                    ['name' => 'Junior (9-14 años)',   'sort_order' => 4],
                    ['name' => 'Uniformes Escolares',  'sort_order' => 5],
                    ['name' => 'Ropa Deportiva',       'sort_order' => 6],
                    ['name' => 'Pijamas',              'sort_order' => 7],
                ],
            ],
            [
                'name'             => 'Ofertas',
                'slug'             => 'ofertas',
                'description'      => 'Las mejores ofertas y descuentos',
                'image'            => null,
                'meta_title'       => 'Ofertas y Descuentos | EM Collective',
                'meta_description' => 'Aprovecha las mejores ofertas en ropa para toda la familia.',
                'sort_order'       => 4,
                'children'         => [],
            ],
        ];

        foreach ($tree as $parentData) {
            $children = $parentData['children'];
            unset($parentData['children']);

            $parent = Category::updateOrCreate(
                ['slug' => $parentData['slug']],
                array_merge($parentData, ['is_active' => true])
            );

            foreach ($children as $childData) {
                Category::updateOrCreate(
                    ['slug' => Str::slug($parent->slug . '-' . $childData['name'])],
                    array_merge($childData, [
                        'parent_id'        => $parent->id,
                        'slug'             => Str::slug($parent->slug . '-' . $childData['name']),
                        'meta_title'       => $childData['name'] . ' | EM Collective',
                        'meta_description' => 'Encuentra ' . strtolower($childData['name']) . ' al mejor precio. Envío a todo el Perú.',
                        'is_active'        => true,
                    ])
                );
            }
        }

        $total = Category::count();
        $this->command->info("✅ Categorías creadas: {$total}");
    }
}
