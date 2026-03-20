<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // ── HOMBRE ─────────────────────────────────────────────────────
            [
                'name'        => 'Polo Básico Algodón',
                'category'    => 'hombre-polos',
                'brand'       => 'Topy Top',
                'gender'      => 'men',
                'base_price'  => 39.90,
                'sale_price'  => null,
                'description' => 'Polo de algodón 100% peinado. Corte regular, cuello redondo. Ideal para el día a día.',
                'details'     => '<p><strong>Material:</strong> 100% algodón peinado</p><p><strong>Corte:</strong> Regular fit</p><p><strong>Cuidado:</strong> Lavado a máquina frío</p>',
                'is_featured' => true,
                'sizes'       => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'colors'      => ['Blanco', 'Negro', 'Gris', 'Azul Marino', 'Rojo'],
                'stock'       => 15,
                'attributes'  => ['material' => 'Algodón 100%', 'corte' => 'Regular fit'],
            ],
            [
                'name'        => 'Camisa Oxford Slim Fit',
                'category'    => 'hombre-camisas',
                'brand'       => 'Studio F',
                'gender'      => 'men',
                'base_price'  => 89.90,
                'sale_price'  => 69.90,
                'description' => 'Camisa Oxford de alta calidad. Corte slim fit, perfecta para ocasiones formales o casuales.',
                'details'     => '<p><strong>Material:</strong> Popelina Oxford 100% algodón</p><p><strong>Corte:</strong> Slim fit</p>',
                'is_featured' => true,
                'sizes'       => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors'      => ['Blanco', 'Azul Cielo', 'Gris Claro', 'Beige'],
                'stock'       => 10,
                'attributes'  => ['material' => 'Popelina Oxford', 'corte' => 'Slim fit'],
            ],
            [
                'name'        => 'Jean Clásico Recto',
                'category'    => 'hombre-pantalones',
                'brand'       => 'Levi\'s',
                'gender'      => 'men',
                'base_price'  => 129.90,
                'sale_price'  => null,
                'description' => 'Jean de corte recto, clásico y versátil. Tela denim de alta resistencia.',
                'details'     => '<p><strong>Material:</strong> 98% algodón, 2% elastano</p><p><strong>Corte:</strong> Regular straight</p>',
                'is_featured' => false,
                'sizes'       => ['28', '29', '30', '31', '32', '33', '34', '36'],
                'colors'      => ['Jean Oscuro', 'Jean Claro', 'Negro'],
                'stock'       => 8,
                'attributes'  => ['material' => 'Denim stretch', 'corte' => 'Regular straight'],
            ],
            [
                'name'        => 'Hoodie Oversize Fleece',
                'category'    => 'hombre-hoodies-y-poleras',
                'brand'       => 'Adidas',
                'gender'      => 'men',
                'base_price'  => 119.90,
                'sale_price'  => 99.90,
                'description' => 'Hoodie oversize con bolsillo canguro y capucha ajustable. Tela fleece suave y abrigadora.',
                'details'     => '<p><strong>Material:</strong> 80% algodón, 20% poliéster fleece</p><p><strong>Corte:</strong> Oversize</p>',
                'is_featured' => true,
                'sizes'       => ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
                'colors'      => ['Negro', 'Gris', 'Verde Militar', 'Beige'],
                'stock'       => 12,
                'attributes'  => ['material' => 'Fleece', 'corte' => 'Oversize'],
            ],

            // ── MUJER ──────────────────────────────────────────────────────
            [
                'name'        => 'Vestido Floral Midi',
                'category'    => 'mujer-vestidos',
                'brand'       => 'Studio F',
                'gender'      => 'women',
                'base_price'  => 99.90,
                'sale_price'  => 79.90,
                'description' => 'Vestido midi con estampado floral. Tela fluida, perfecta para el verano.',
                'details'     => '<p><strong>Material:</strong> 100% viscosa</p><p><strong>Largo:</strong> Midi (debajo de la rodilla)</p>',
                'is_featured' => true,
                'sizes'       => ['XS', 'S', 'M', 'L', 'XL'],
                'colors'      => ['Multicolor', 'Rosa', 'Coral'],
                'stock'       => 10,
                'attributes'  => ['material' => 'Viscosa', 'largo' => 'Midi'],
            ],
            [
                'name'        => 'Blusa Oversize Lino',
                'category'    => 'mujer-blusas-y-tops',
                'brand'       => 'Zara',
                'gender'      => 'women',
                'base_price'  => 79.90,
                'sale_price'  => null,
                'description' => 'Blusa de lino oversize con mangas largas y cuello en V. Elegante y cómoda.',
                'details'     => '<p><strong>Material:</strong> 100% lino</p><p><strong>Cuello:</strong> V</p>',
                'is_featured' => false,
                'sizes'       => ['XS', 'S', 'M', 'L', 'XL'],
                'colors'      => ['Blanco', 'Beige', 'Verde Menta', 'Lavanda'],
                'stock'       => 14,
                'attributes'  => ['material' => 'Lino', 'cuello' => 'V'],
            ],
            [
                'name'        => 'Jean Mom Fit Tiro Alto',
                'category'    => 'mujer-pantalones-y-jeans',
                'brand'       => 'Basement',
                'gender'      => 'women',
                'base_price'  => 119.90,
                'sale_price'  => 95.90,
                'description' => 'Jean mom fit de tiro alto. Silueta relajada en la cadera y muslos, ajustada abajo.',
                'details'     => '<p><strong>Material:</strong> 97% algodón, 3% elastano</p><p><strong>Tiro:</strong> Alto</p>',
                'is_featured' => true,
                'sizes'       => ['28', '29', '30', '31', '32', '33', '34'],
                'colors'      => ['Jean Claro', 'Jean Oscuro', 'Negro', 'Blanco'],
                'stock'       => 9,
                'attributes'  => ['material' => 'Denim', 'tiro' => 'Alto'],
            ],
            [
                'name'        => 'Casaca Cuero Sintético',
                'category'    => 'mujer-casacas-y-abrigos',
                'brand'       => 'H&M',
                'gender'      => 'women',
                'base_price'  => 159.90,
                'sale_price'  => 129.90,
                'description' => 'Casaca de cuero sintético. Estilo biker clásico con bolsillos y cierre metálico.',
                'details'     => '<p><strong>Material:</strong> 100% poliuretano (cuero sintético)</p><p><strong>Forro:</strong> 100% poliéster</p>',
                'is_featured' => true,
                'sizes'       => ['XS', 'S', 'M', 'L', 'XL'],
                'colors'      => ['Negro', 'Camel', 'Marrón'],
                'stock'       => 6,
                'attributes'  => ['material' => 'Cuero sintético', 'estilo' => 'Biker'],
            ],

            // ── NIÑOS ──────────────────────────────────────────────────────
            [
                'name'        => 'Conjunto Bebé Algodón',
                'category'    => 'ninos-bebe-0-2-anos',
                'brand'       => 'Sin Marca',
                'gender'      => 'kids',
                'base_price'  => 49.90,
                'sale_price'  => null,
                'description' => 'Conjunto de bebé en algodón suave. Incluye mameluco + gorrito. Suave para la piel del bebé.',
                'details'     => '<p><strong>Material:</strong> 100% algodón pima</p><p><strong>Incluye:</strong> Mameluco + gorrito</p>',
                'is_featured' => false,
                'sizes'       => ['0-3 meses', '3-6 meses', '6-12 meses', '12-18 meses'],
                'colors'      => ['Blanco', 'Rosa Palo', 'Azul Cielo', 'Amarillo'],
                'stock'       => 20,
                'attributes'  => ['material' => 'Algodón pima', 'incluye' => 'Mameluco + gorrito'],
            ],
            [
                'name'        => 'Polo Dinosaurio Niño',
                'category'    => 'ninos-nino-3-8-anos',
                'brand'       => 'Topy Top',
                'gender'      => 'kids',
                'base_price'  => 29.90,
                'sale_price'  => null,
                'description' => 'Polo estampado con diseño de dinosaurio. Algodón suave, ideal para niños activos.',
                'details'     => '<p><strong>Material:</strong> 100% algodón</p><p><strong>Estampado:</strong> Serigrafía en pecho</p>',
                'is_featured' => false,
                'sizes'       => ['Talla 2', 'Talla 3', 'Talla 4', 'Talla 5', 'Talla 6', 'Talla 8'],
                'colors'      => ['Verde', 'Azul', 'Naranja', 'Rojo'],
                'stock'       => 25,
                'attributes'  => ['material' => 'Algodón', 'estampado' => 'Dinosaurio'],
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('slug', $data['category'])->first();
            $brand    = Brand::where('name', $data['brand'])->first();

            if (!$category) {
                $this->command->warn("⚠️  Categoría no encontrada: {$data['category']}");
                continue;
            }

            $product = Product::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'category_id'      => $category->id,
                    'brand_id'         => $brand?->id,
                    'name'             => $data['name'],
                    'slug'             => Str::slug($data['name']),
                    'description'      => $data['description'],
                    'details'          => $data['details'],
                    'base_price'       => $data['base_price'],
                    'sale_price'       => $data['sale_price'],
                    'gender'           => $data['gender'],
                    'is_active'        => true,
                    'is_featured'      => $data['is_featured'],
                    'attributes'       => $data['attributes'] ?? null,
                    'meta_title'       => $data['name'] . ' | EM Collective',
                    'meta_description' => $data['description'],
                ]
            );

            // Crear variantes: combinación talla × color
            foreach ($data['sizes'] as $sizeName) {
                $size = Size::where('name', $sizeName)->first();
                if (!$size) continue;

                foreach ($data['colors'] as $colorName) {
                    $color = Color::where('name', $colorName)->first();
                    if (!$color) continue;

                    ProductVariant::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'size_id'    => $size->id,
                            'color_id'   => $color->id,
                        ],
                        [
                            'sku'            => strtoupper(Str::random(3)) . '-' . $size->code . '-' . $color->id,
                            'stock'          => $data['stock'],
                            'price_modifier' => 0,
                            'is_active'      => true,
                        ]
                    );
                }
            }
        }

        $productCount = Product::count();
        $variantCount = ProductVariant::count();
        $this->command->info("✅ Productos demo: {$productCount} productos, {$variantCount} variantes");
    }
}
