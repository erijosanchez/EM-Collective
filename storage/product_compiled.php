<?php use Illuminate\Support\Str; ?>


<?php $__env->startSection('title', $product->meta_title ?? $product->name . ' | EM Collective'); ?><?php $__env->stopSection(); ?>
<?php $__env->startSection('description', $product->meta_description ?? Str::limit(strip_tags($product->description), 160)); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('og_type', 'product'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('og_title', $product->name . ' | EM Collective'); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('og_description', Str::limit(strip_tags($product->description ?? ''), 160)); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('og_image', $product->images->first() ? asset('storage/' . $product->images->first()->path) :
    asset('img/og-default.jpg')); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('json_ld'); ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "<?php echo e(addslashes($product->name)); ?>",
  "description": "<?php echo e(addslashes(Str::limit(strip_tags($product->description ?? ''), 300))); ?>",
  "sku": "<?php echo e($product->sku); ?>",
  "brand": { "@type": "Brand", "name": "<?php echo e($product->brand?->name ?? 'EM Collective'); ?>" },
  "offers": {
    "@type": "Offer",
    "url": "<?php echo e(route('product.show', $product->slug)); ?>",
    "priceCurrency": "PEN",
    "price": "<?php echo e($product->current_price); ?>",
    "availability": "<?php echo e($product->total_stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock'); ?>"
  }
  <?php
      $jsonExtras = '';
      if ($product->images->first()) {
          $jsonExtras .= ',"image": "' . asset('storage/' . $product->images->first()->path) . '"';
      }
      if ($product->approvedReviews->count()) {
          $jsonExtras .= ',"aggregateRating": {"@@type": "AggregateRating","ratingValue": "' . number_format($product->average_rating, 1) . '","reviewCount": "' . $product->approvedReviews->count() . '"}';
      }
      echo $jsonExtras;
  ?>
}
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="mx-auto px-4 sm:px-6 py-8 max-w-7xl">

        
        <nav class="mb-8 text-stone text-xs uppercase tracking-widest">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-carbon">Inicio</a>
            <?php if($product->category): ?>
                <span class="mx-2">/</span>
                <a href="<?php echo e(route('category.show', $product->category->slug)); ?>"
                    class="hover:text-carbon"><?php echo e($product->category->name); ?></a>
            <?php endif; ?>
            <span class="mx-2">/</span>
            <span class="text-carbon"><?php echo e($product->name); ?></span>
        </nav>

        <div class="gap-8 lg:gap-16 grid grid-cols-1 md:grid-cols-2">

            
            <div x-data="{ active: 0, zoomed: false, zX: 50, zY: 50 }" class="flex sm:flex-row flex-col-reverse gap-4">

                
                <?php if($product->images->count() > 1): ?>
                    <div class="flex sm:flex-col flex-shrink-0 gap-2 sm:w-20 overflow-x-auto sm:overflow-y-auto">
                        <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button @click="active = <?php echo e($i); ?>; zoomed = false"
                                class="flex-shrink-0 border-2 w-16 sm:w-20 h-16 sm:h-20 overflow-hidden transition-colors"
                                :class="active === <?php echo e($i); ?> ? 'border-carbon' :
                                    'border-transparent hover:border-stone/40'">
                                <img src="<?php echo e(asset('storage/' . $image->path)); ?>" alt=""
                                    class="w-full h-full object-cover">
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                
                <div class="relative flex-1 bg-stone/10 aspect-[3/4] overflow-hidden select-none"
                    :class="zoomed ? 'cursor-zoom-out' : 'cursor-zoom-in'" @mouseenter="zoomed = true"
                    @mouseleave="zoomed = false"
                    @mousemove="
                        let r = $el.getBoundingClientRect();
                        zX = ((event.clientX - r.left) / r.width * 100).toFixed(2);
                        zY = ((event.clientY - r.top)  / r.height * 100).toFixed(2);
                    ">
                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img x-show="active === <?php echo e($i); ?>" src="<?php echo e(asset('storage/' . $image->path)); ?>"
                            alt="<?php echo e($product->name); ?>"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-100 pointer-events-none will-change-transform"
                            :style="zoomed
                                ?
                                `transform:scale(2.2);transform-origin:${zX}% ${zY}%` :
                                'transform:scale(1)'">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($product->images->isEmpty()): ?>
                        <div class="flex justify-center items-center bg-stone/20 w-full h-full">
                            <svg class="w-24 h-24 text-stone/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    <?php endif; ?>

                    
                    <?php if($product->is_on_sale): ?>
                        <span
                            class="top-4 left-4 absolute bg-terracota px-3 py-1 text-white text-xs uppercase tracking-wider pointer-events-none">
                            -<?php echo e($product->discount_percentage); ?>%
                        </span>
                    <?php endif; ?>

                    
                    <span
                        class="hidden right-3 bottom-3 absolute sm:flex items-center gap-1 bg-carbon/50 px-2 py-1 rounded text-[10px] text-cream/80 pointer-events-none"
                        x-show="!zoomed">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                        </svg>
                        Zoom
                    </span>
                </div>
            </div>

            
            <?php
                $colorsData = $product->variants
                    ->pluck('color')
                    ->filter()
                    ->unique('id')
                    ->map(fn($c) => ['id' => $c->id, 'name' => $c->name])
                    ->values();
            ?>

            <div x-data="{
                selectedColor: null,
                selectedSize: null,
                quantity: 1,
                sizeGuideOpen: false,
                colors: <?php echo e($colorsData->toJson()); ?>,
                variants: <?php echo e($product->variants->map(
                        fn($v) => [
                            'id' => $v->id,
                            'size_id' => $v->size_id,
                            'color_id' => $v->color_id,
                            'stock' => $v->stock,
                            'price' => $v->final_price,
                        ],
                    )->toJson()); ?>,
                get currentVariant() {
                    if (!this.selectedSize && !this.selectedColor) return null;
                    return this.variants.find(v =>
                        (!this.selectedSize || v.size_id == this.selectedSize) &&
                        (!this.selectedColor || v.color_id == this.selectedColor)
                    ) || null;
                },
                get currentPrice() {
                    return this.currentVariant ? this.currentVariant.price : <?php echo e((float) $product->current_price); ?>;
                },
                get stock() {
                    return this.currentVariant ? this.currentVariant.stock : <?php echo e((int) $product->total_stock); ?>;
                },
                get canAdd() {
                    return this.stock > 0;
                },
                get selectedColorName() {
                    if (!this.selectedColor) return '';
                    const c = this.colors.find(c => c.id === this.selectedColor);
                    return c ? c.name : '';
                },
                sizeInStock(sizeId) {
                    return this.variants.some(v => v.size_id == sizeId && v.stock > 0 &&
                        (!this.selectedColor || v.color_id == this.selectedColor));
                },
                colorInStock(colorId) {
                    return this.variants.some(v => v.color_id == colorId && v.stock > 0 &&
                        (!this.selectedSize || v.size_id == this.selectedSize));
                }
            }">

                
                <?php if($product->brand): ?>
                    <p class="mb-2 text-terracota text-xs uppercase tracking-widest"><?php echo e($product->brand->name); ?></p>
                <?php endif; ?>

                <h1 class="mb-4 font-serif font-light text-2xl sm:text-3xl lg:text-4xl leading-tight"><?php echo e($product->name); ?>

                </h1>

                
                <div class="flex items-center gap-3 mb-6">
                    <span class="font-sans font-medium text-2xl" x-text="'S/ ' + currentPrice.toFixed(2)">
                        S/ <?php echo e(number_format($product->current_price, 2)); ?>

                    </span>
                    <?php if($product->is_on_sale): ?>
                        <span class="text-stone text-sm line-through">S/
                            <?php echo e(number_format($product->base_price, 2)); ?></span>
                        <span
                            class="bg-terracota/10 px-2 py-0.5 text-terracota text-xs">-<?php echo e($product->discount_percentage); ?>%</span>
                    <?php endif; ?>
                </div>

                
                <?php if($product->approvedReviews->count()): ?>
                    <div class="flex items-center gap-2 mb-6">
                        <div class="flex">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <svg class="w-4 h-4 <?php echo e($i <= $product->average_rating ? 'text-terracota' : 'text-stone/30'); ?>"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            <?php endfor; ?>
                        </div>
                        <span class="text-stone text-xs">(<?php echo e($product->approvedReviews->count()); ?> reseñas)</span>
                    </div>
                <?php endif; ?>

                
                <?php $colors = $product->variants->pluck('color')->filter()->unique('id'); ?>
                <?php if($colors->count()): ?>
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs uppercase tracking-widest">Color</span>
                            
                            <span class="text-stone text-xs" x-show="selectedColor" x-text="selectedColorName"></span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button @click="selectedColor = <?php echo e($color->id); ?>"
                                    :class="{
                                        'ring-2 ring-offset-1 ring-carbon': selectedColor === <?php echo e($color->id); ?>,
                                        'opacity-40 cursor-not-allowed': !colorInStock(<?php echo e($color->id); ?>)
                                    }"
                                    title="<?php echo e($color->name); ?>"
                                    class="border border-stone/30 rounded-full w-10 sm:w-8 h-10 sm:h-8 transition-all"
                                    style="background: <?php echo e($color->hex_code); ?>">
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                
                <?php $sizes = $product->variants->pluck('size')->filter()->unique('id')->sortBy('sort_order'); ?>
                <?php if($sizes->count()): ?>
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs uppercase tracking-widest">Talla</span>
                            <button type="button" @click="sizeGuideOpen = true"
                                class="text-stone hover:text-carbon text-xs underline transition-colors">
                                Guía de tallas
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button @click="sizeInStock(<?php echo e($size->id); ?>) && (selectedSize = <?php echo e($size->id); ?>)"
                                    :class="{
                                        'bg-carbon text-cream border-carbon': selectedSize === <?php echo e($size->id); ?>,
                                        'border-stone/30 text-stone line-through cursor-not-allowed': !sizeInStock(
                                            <?php echo e($size->id); ?>),
                                        'border-stone/30 text-carbon hover:border-carbon': sizeInStock(
                                            <?php echo e($size->id); ?>) && selectedSize !== <?php echo e($size->id); ?>

                                    }"
                                    class="px-3 py-3 sm:py-2 border min-w-[3rem] text-xs uppercase tracking-wider transition-all">
                                    <?php echo e($size->name); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                
                <div class="mb-6">
                    <p class="text-xs" :class="stock > 5 ? 'text-stone' : 'text-terracota'">
                        <span x-show="stock > 10">✓ Disponible en stock</span>
                        <span x-show="stock > 0 && stock <= 10">⚡ Solo <span x-text="stock"></span> disponibles</span>
                        <span x-show="stock === 0">✕ Sin stock disponible</span>
                    </p>
                </div>

                
                <div class="flex gap-3 mb-6">
                    
                    <div class="flex border border-stone/30">
                        <button @click="if(quantity > 1) quantity--"
                            class="px-3 py-3 text-stone hover:text-carbon transition-colors">−</button>
                        <span x-text="quantity"
                            class="px-4 py-3 border-stone/30 border-x min-w-[3rem] text-sm text-center"></span>
                        
                        <button @click="if(stock > 0 && quantity < stock) quantity++"
                            class="px-3 py-3 text-stone hover:text-carbon transition-colors">+</button>
                    </div>

                    
                    <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="flex-1">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                        <input type="hidden" name="quantity" :value="quantity">
                        
                        <input type="hidden" name="variant_id" :value="currentVariant?.id ?? ''">
                        <button type="submit" :disabled="!canAdd"
                            class="disabled:opacity-50 py-3 w-full disabled:cursor-not-allowed btn-primary">
                            <span x-show="canAdd">Agregar al carrito</span>
                            <span x-show="!canAdd">Sin stock</span>
                        </button>
                    </form>
                </div>

                
                <?php if(auth()->guard()->check()): ?>
                    <form action="<?php echo e(route('account.wishlist.toggle', $product->id)); ?>" method="POST" class="mb-8">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="flex items-center gap-2 text-stone hover:text-terracota text-sm transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Guardar en wishlist
                        </button>
                    </form>
                <?php endif; ?>

                
                <div class="space-y-0 border-stone/20 border-t">
                    
                    <div x-data="{ open: true }" class="border-stone/20 border-b">
                        <button @click="open = !open"
                            class="flex justify-between items-center py-4 w-full text-xs uppercase tracking-widest">
                            Descripción
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" x-transition class="pb-4 text-stone text-sm leading-relaxed">
                            <?php echo e($product->description); ?>

                        </div>
                    </div>

                    
                    <?php if($product->details): ?>
                        <div x-data="{ open: false }" class="border-stone/20 border-b">
                            <button @click="open = !open"
                                class="flex justify-between items-center py-4 w-full text-xs uppercase tracking-widest">
                                Especificaciones
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-transition class="pb-4 max-w-none text-stone text-sm prose prose-sm">
                                <?php echo $product->details; ?>

                            </div>
                        </div>
                    <?php endif; ?>

                    
                    <div x-show="sizeGuideOpen" @keydown.escape.window="sizeGuideOpen = false"
                        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display:none"
                        class="z-50 fixed inset-0 flex justify-center items-end sm:items-center p-0 sm:p-4">

                        <div class="fixed inset-0 bg-carbon/60" @click="sizeGuideOpen = false"></div>

                        <div class="relative bg-cream sm:rounded-none rounded-t-2xl w-full sm:max-w-xl max-h-[90vh] overflow-y-auto"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="translate-y-8 opacity-0"
                            x-transition:enter-end="translate-y-0 opacity-100">

                            <div class="sm:hidden flex justify-center mb-1 pt-3">
                                <div class="bg-stone/30 rounded-full w-10 h-1"></div>
                            </div>

                            <div class="flex justify-between items-center px-6 py-4 border-stone/20 border-b">
                                <h3 class="font-serif font-light text-xl">Guía de Tallas</h3>
                                <button @click="sizeGuideOpen = false" class="p-1 text-stone hover:text-carbon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-6 p-6">
                                <p class="text-stone text-sm">Todas las medidas están en centímetros y corresponden a las
                                    medidas del cuerpo, no de la prenda.</p>

                                
                                <div>
                                    <h4 class="mb-3 text-xs uppercase tracking-widest">Mujer</h4>
                                    <div class="overflow-x-auto">
                                        <table class="w-full min-w-[360px] text-xs">
                                            <thead>
                                                <tr class="bg-carbon text-cream">
                                                    <th class="px-3 py-2.5 font-normal text-left uppercase tracking-wider">
                                                        Talla</th>
                                                    <th class="px-3 py-2.5 font-normal text-left uppercase tracking-wider">
                                                        Pecho</th>
                                                    <th class="px-3 py-2.5 font-normal text-left uppercase tracking-wider">
                                                        Cintura</th>
                                                    <th class="px-3 py-2.5 font-normal text-left uppercase tracking-wider">
                                                        Cadera</th>
                                                    <th class="px-3 py-2.5 font-normal text-left uppercase tracking-wider">
                                                        Largo</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-stone/10">
                                                <tr class="hover:bg-stone/5">
                                                    <td class="px-3 py-2.5 font-medium">XS</td>
                                                    <td class="px-3">80-83</td>
                                                    <td class="px-3">62-65</td>
                                                    <td class="px-3">86-89</td>
                                                    <td class="px-3">155-160</td>
                                                </tr>
                                                <tr class="hover:bg-stone/5">
                                                    <td class="px-3 py-2.5 font-medium">S</td>
                                                    <td class="px-3">84-87</td>
                                                    <td class="px-3">66-69</td>
                                                    <td class="px-3">90-93</td>
                                                    <td class="px-3">160-165</td>
                                                </tr>
                                                <tr class="hover:bg-stone/5">
                                                    <td class="px-3 py-2.5 font-medium">M</td>
                                                    <td class="px-3">88-91</td>
                                                    <td class="px-3">70-73</td>
                                                    <td class="px-3">94-97</td>
                                                    <td class="px-3">162-167</td>
                                                </tr>
                                                <tr class="hover:bg-stone/5">
                                                    <td class="px-3 py-2.5 font-medium">L</td>
                                                    <td class="px-3">92-95</td>
                                                    <td class="px-3">74-77</td>
                                                    <td class="px-3">98-101</td>
                                                    <td class="px-3">165-170</td>
                                                </tr>
                                                <tr class="hover:bg-stone/5">
                                                    <td class="px-3 py-2.5 font-medium">XL</td>
                                                    <td class="px-3">96-99</td>
                                                    <td class="px-3">78-81</td>
                                                    <td class="px-3">102-105</td>
                                                    <td class="px-3">167-172</td>
                                                </tr>
                                                <tr class="hover:bg-stone/5">
                                                    <td class="px-3 py-2.5 font-medium">XXL</td>
                                                    <td class="px-3">100-105</td>
                                                    <td class="px-3">82-87</td>
                                                    <td class="px-3">106-111</td>
                                                    <td class="px-3">167-172</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                
                                <div>
                                    <h4 class="mb-3 text-xs uppercase tracking-widest">Hombre</h4>
                                    <div class="overflow-x-auto">
                                        <table class="w-full min-w-[360px] text-xs">
                                            <thead>
                                                <tr class="bg-carbon text-cream">
                                                    <th class="px-3 py-2.5 font-normal text-left uppercase tracking-wider">
                                                        Talla</th>
                                                    <th class="px-3 py-2.5 font-normal text-left uppercase tracking-wider">
                                                        Pecho</th>
                                                    <th class="px-3 py-2.5 font-normal text-left uppercase tracking-wider">
                                                        Cintura</th>
                                                    <th class="px-3 py-2.5 font-normal text-left uppercase tracking-wider">
                                                        Hombros</th>
                                                    <th class="px-3 py-2.5 font-normal text-left uppercase tracking-wider">
                                                        Talla INT</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-stone/10">
                                                <tr class="hover:bg-stone/5">
                                                    <td class="px-3 py-2.5 font-medium">S</td>
                                                    <td class="px-3">88-92</td>
                                                    <td class="px-3">74-78</td>
                                                    <td class="px-3">42-43</td>
                                                    <td class="px-3">S / 36</td>
                                                </tr>
                                                <tr class="hover:bg-stone/5">
                                                    <td class="px-3 py-2.5 font-medium">M</td>
                                                    <td class="px-3">93-97</td>
                                                    <td class="px-3">79-83</td>
                                                    <td class="px-3">44-45</td>
                                                    <td class="px-3">M / 38</td>
                                                </tr>
                                                <tr class="hover:bg-stone/5">
                                                    <td class="px-3 py-2.5 font-medium">L</td>
                                                    <td class="px-3">98-102</td>
                                                    <td class="px-3">84-88</td>
                                                    <td class="px-3">46-47</td>
                                                    <td class="px-3">L / 40</td>
                                                </tr>
                                                <tr class="hover:bg-stone/5">
                                                    <td class="px-3 py-2.5 font-medium">XL</td>
                                                    <td class="px-3">103-108</td>
                                                    <td class="px-3">89-94</td>
                                                    <td class="px-3">48-49</td>
                                                    <td class="px-3">XL / 42</td>
                                                </tr>
                                                <tr class="hover:bg-stone/5">
                                                    <td class="px-3 py-2.5 font-medium">XXL</td>
                                                    <td class="px-3">109-115</td>
                                                    <td class="px-3">95-101</td>
                                                    <td class="px-3">50-51</td>
                                                    <td class="px-3">XXL / 44</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="space-y-1 bg-stone/10 p-4 text-stone text-xs">
                                    <p>💡 <strong class="text-carbon">¿Entre dos tallas?</strong> Elige la talla mayor.</p>
                                    <p>📏 Mide el contorno de pecho, cintura y cadera con una cinta métrica.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <?php if($product->approvedReviews->count()): ?>
            <div class="mt-16 pt-12 border-stone/20 border-t">
                <h2 class="mb-8 font-serif font-light text-3xl">Reseñas de clientes</h2>
                <div class="gap-6 grid sm:grid-cols-2 lg:grid-cols-3">
                    <?php $__currentLoopData = $product->approvedReviews->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white p-6 border border-stone/20">
                            <div class="flex mb-2">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <svg class="w-4 h-4 <?php echo e($i <= $review->rating ? 'text-terracota' : 'text-stone/20'); ?>"
                                        fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                <?php endfor; ?>
                            </div>
                            <?php if($review->title): ?>
                                <h4 class="mb-1 font-sans font-medium text-sm"><?php echo e($review->title); ?></h4>
                            <?php endif; ?>
                            <p class="text-stone text-sm leading-relaxed"><?php echo e($review->body); ?></p>
                            <p class="mt-3 text-stone text-xs"><?php echo e($review->user?->name); ?> ·
                                <?php echo e($review->created_at->diffForHumans()); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if($relatedProducts->count()): ?>
            <div class="mt-16 pt-12 border-stone/20 border-t">
                <h2 class="mb-8 font-serif font-light text-3xl">También te puede gustar</h2>
                <div class="gap-4 sm:gap-6 grid grid-cols-2 sm:grid-cols-4">
                    <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('shop._product-card', ['product' => $related], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        
        <div x-data="recentlyViewedSection()" x-init="load()">
            <template x-if="items.length > 0">
                <div class="mt-16 pt-12 border-stone/20 border-t">
                    <h2 class="mb-8 font-serif font-light text-3xl">Vistos recientemente</h2>
                    <div class="gap-4 sm:gap-6 grid grid-cols-2 sm:grid-cols-4">
                        <template x-for="p in items" :key="p.id">
                            <a :href="p.url" class="group">
                                <div class="bg-stone/10 mb-3 aspect-[3/4] overflow-hidden">
                                    <img :src="p.image" :alt="p.name"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        x-on:error="$el.style.opacity='0'">
                                </div>
                                <p class="mb-1 font-sans text-carbon text-xs line-clamp-2 leading-snug" x-text="p.name">
                                </p>
                                <p class="font-medium text-sm" x-text="'S/ ' + (p.price || 0).toFixed(2)"></p>
                            </a>
                        </template>
                    </div>
                </div>
            </template>
        </div>

    </div>

    <script>
        // ── Tracking: guarda este producto en el historial local ──────────────
        (function() {
            const current = {
                id: <?php echo e($product->id); ?>,
                name: <?php echo e(json_encode($product->name)); ?>,
                price: <?php echo e((float) $product->current_price); ?>,
                image: <?php echo e(json_encode($product->primary_image ? asset('storage/' . $product->primary_image) : '')); ?>,
                url: <?php echo e(json_encode(route('product.show', $product->slug))); ?>,
            };
            try {
                let rv = JSON.parse(localStorage.getItem('em_rv') || '[]');
                rv = rv.filter(p => p.id !== current.id);
                rv.unshift(current);
                localStorage.setItem('em_rv', JSON.stringify(rv.slice(0, 8)));
            } catch (e) {}
        })();

        // ── Sección de recientemente vistos ──────────────────────────────────
        function recentlyViewedSection() {
            return {
                items: [],
                load() {
                    try {
                        const rv = JSON.parse(localStorage.getItem('em_rv') || '[]');
                        this.items = rv.filter(p => p.id !== <?php echo e($product->id); ?>).slice(0, 4);
                    } catch (e) {
                        this.items = [];
                    }
                }
            };
        }
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>