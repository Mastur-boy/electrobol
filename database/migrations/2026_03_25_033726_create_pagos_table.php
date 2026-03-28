<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reparacion_id')->constrained('reparaciones')->onDelete('cascade');
            $table->decimal('monto', 10, 2);
            $table->enum('tipo', ['evaluacion', 'reparacion']);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'transferencia', 'qr']); 
            $table->date('fecha_pago');
            $table->string('comprobante')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};