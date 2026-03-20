<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->enum('type', ['percentage', 'fixed']); // % o monto fijo
            $table->decimal('value', 10, 2); // 20 = 20% o S/ 20
            $table->decimal('min_order_amount', 10, 2)->default(0); // mínimo de compra
            $table->decimal('max_discount', 10, 2)->nullable(); // tope de descuento
            $table->integer('usage_limit')->nullable(); // usos totales permitidos
            $table->integer('usage_limit_per_user')->default(1); // usos por usuario
            $table->integer('used_count')->default(0);
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete(); // solo para esa cat
            $table->boolean('is_active')->default(true);
            $table->datetime('starts_at')->nullable();
            $table->datetime('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // ORD-2024-00001
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // null = invitado
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();

            // Datos del cliente (invitado o snapshot del registrado)
            $table->string('guest_email')->nullable();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone', 20);
            $table->string('customer_dni', 15)->nullable();

            // Dirección de envío (snapshot)
            $table->string('shipping_department');
            $table->string('shipping_province');
            $table->string('shipping_district');
            $table->string('shipping_address');
            $table->string('shipping_reference')->nullable();

            // Totales
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            // Pago
            $table->enum('payment_method', ['mercadopago', 'contra_entrega']);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->string('payment_reference')->nullable(); // ID de Mercado Pago

            // Estado del pedido
            $table->enum('status', [
                'pending',      // pendiente de pago
                'confirmed',    // pago confirmado
                'processing',   // preparando
                'shipped',      // enviado
                'delivered',    // entregado
                'cancelled',    // cancelado
                'refunded',     // reembolsado
            ])->default('pending');

            $table->text('notes')->nullable(); // notas del cliente
            $table->text('admin_notes')->nullable(); // notas internas
            $table->string('tracking_code')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['status', 'payment_status']);
            $table->index('order_number');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();

            // Snapshot del producto al momento de la compra
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->string('variant_info')->nullable(); // "Talla M / Color Azul"
            $table->string('product_image')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });

        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_email')->nullable();
            $table->decimal('discount_applied', 10, 2);
            $table->timestamps();

            $table->unique(['coupon_id', 'order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('coupons');
    }
};
