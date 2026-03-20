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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->date('birthdate')->nullable()->after('avatar');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birthdate');
            $table->enum('role', ['admin', 'customer'])->default('customer')->after('gender');
            $table->boolean('is_active')->default(true)->after('role');
            $table->boolean('newsletter')->default(false)->after('is_active');
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            // Para invitados se guarda directo en el pedido, para usuarios registrados aquí
            $table->string('label')->nullable(); // "Casa", "Trabajo"
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone', 20);
            $table->string('dni', 15)->nullable();
            $table->string('department');
            $table->string('province');
            $table->string('district');
            $table->string('address'); // calle, número
            $table->string('reference')->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'avatar', 'birthdate', 'gender', 'role', 'is_active', 'newsletter']);
        });
    }
};
