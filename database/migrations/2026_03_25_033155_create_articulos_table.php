<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('tipo');
            $table->string('marca');
            $table->string('modelo');
            $table->string('serial')->nullable();
            $table->boolean('tiene_garantia')->default(false);
            $table->date('fecha_garantia')->nullable();
            $table->text('problema_descripcion');
            $table->boolean('evaluacion_realizada')->default(false);
            $table->boolean('reparacion_aceptada')->nullable();
            $table->decimal('costo_evaluacion', 10, 2)->default(1008);
            $table->timestamps();
            
            // Índices para mejorar rendimiento
            $table->index('cliente_id');
            $table->index('tipo');
            $table->index('marca');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};