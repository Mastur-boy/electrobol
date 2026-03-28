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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ci')->unique()->nullable(); // 👈 Campo CI agregado
            $table->string('email')->unique();
            $table->string('password');
            
            // Campos para tu lógica personalizada
            $table->string('role')->default('user'); // admin o user
            $table->boolean('is_active')->default(false); // Acceso controlado
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};