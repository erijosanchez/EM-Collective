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
        Schema::table('banners', function (Blueprint $table) {
            if (!Schema::hasColumn('banners', 'text_align')) {
                $table->string('text_align', 20)->default('left')->nullable()->after('text_color');
            }
            if (!Schema::hasColumn('banners', 'text_valign')) {
                $table->string('text_valign', 20)->default('middle')->nullable()->after('text_align');
            }
            if (!Schema::hasColumn('banners', 'font_family')) {
                $table->string('font_family', 50)->default('serif')->nullable()->after('text_valign');
            }
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            foreach (['text_valign', 'font_family'] as $col) {
                if (Schema::hasColumn('banners', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
