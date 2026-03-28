<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tecnico;

class TecnicoSeeder extends Seeder
{
    public function run(): void
    {
        Tecnico::create([
            'nombre' => 'Carlos',
            'apellido' => 'Mendoza',
            'especialidad' => 'Electrónica',
            'telefono' => '76543210',
            'email' => 'carlos@electrobol.com',
            'activo' => true
        ]);

        Tecnico::create([
            'nombre' => 'Ana',
            'apellido' => 'Quispe',
            'especialidad' => 'Refrigeración',
            'telefono' => '76543211',
            'email' => 'ana@electrobol.com',
            'activo' => true
        ]);

        Tecnico::create([
            'nombre' => 'Luis',
            'apellido' => 'Torrez',
            'especialidad' => 'Línea Blanca',
            'telefono' => '76543212',
            'email' => 'luis@electrobol.com',
            'activo' => true
        ]);
    }
}
