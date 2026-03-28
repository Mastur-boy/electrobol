<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Repuesto;

class RepuestoSeeder extends Seeder
{
    public function run(): void
    {
        $repuestos = [
            ['codigo' => 'RES-001', 'nombre' => 'Resistencia Eléctrica', 'stock_actual' => 15, 'stock_minimo' => 5, 'precio_unitario' => 85.50, 'proveedor' => 'ElectroPartes'],
            ['codigo' => 'MOT-001', 'nombre' => 'Motor Ventilador', 'stock_actual' => 8, 'stock_minimo' => 3, 'precio_unitario' => 150.00, 'proveedor' => 'Motores Bolivia'],
            ['codigo' => 'PLA-001', 'nombre' => 'Placa Electrónica', 'stock_actual' => 5, 'stock_minimo' => 2, 'precio_unitario' => 220.00, 'proveedor' => 'ElectroPartes'],
            ['codigo' => 'TER-001', 'nombre' => 'Termostato', 'stock_actual' => 12, 'stock_minimo' => 4, 'precio_unitario' => 45.00, 'proveedor' => 'RefriPartes'],
            ['codigo' => 'COM-001', 'nombre' => 'Compresor', 'stock_actual' => 3, 'stock_minimo' => 2, 'precio_unitario' => 450.00, 'proveedor' => 'RefriPartes'],
        ];
        
        foreach ($repuestos as $repuesto) {
            Repuesto::create($repuesto);
        }
    }
}
