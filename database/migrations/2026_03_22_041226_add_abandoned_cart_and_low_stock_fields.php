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
        Schema::table('carts', function (Blueprint $table) {
            $table->string('user_email')->nullable()->after('user_id');
            $table->boolean('abandoned_email_sent')->default(false)->after('coupon_id');
            $table->timestamp('last_active_at')->nullable()->after('abandoned_email_sent');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('low_stock_threshold')->default(5)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['user_email', 'abandoned_email_sent', 'last_active_at']);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('low_stock_threshold');
        });
    }
};
