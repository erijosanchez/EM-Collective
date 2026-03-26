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
            $table->string('text_bg_color', 7)->nullable()->after('font_family');  // hex, ej: #000000
            $table->tinyInteger('text_bg_opacity')->default(0)->after('text_bg_color'); // 0-90
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['text_bg_color', 'text_bg_opacity']);
        });
    }
};
