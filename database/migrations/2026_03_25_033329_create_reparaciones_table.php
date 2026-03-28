<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reparaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('articulo_id')->constrained('articulos')->onDelete('cascade');
            $table->foreignId('tecnico_id')->constrained('tecnicos')->onDelete('cascade');
            $table->enum('estado', ['evaluacion', 'reparacion', 'entregado'])->default('evaluacion');
            $table->text('diagnostico')->nullable();
            $table->text('solucion')->nullable();
            $table->decimal('tiempo_estimado_horas', 8, 2)->nullable();
            $table->decimal('costo_reparacion', 10, 2)->nullable();
            $table->date('fecha_asignacion');
            $table->date('fecha_inicio_reparacion')->nullable();
            $table->date('fecha_fin_reparacion')->nullable();
            $table->date('fecha_entrega')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reparaciones');
    }
};