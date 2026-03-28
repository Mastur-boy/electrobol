<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        Cliente::create([
            'nombre' => 'Juana',
            'apellido' => 'Salomon',
            'ci' => '123456789',           
            'email' => 'juana@example.com',
            'telefono' => '70000000',
            'direccion' => 'Av. Siempre Viva 123',
        ]);

        Cliente::create([
            'nombre' => 'Mario',
            'apellido' => 'Mendoza',
            'ci' => '987654321',           
            'email' => 'mario@example.com',
            'telefono' => '70000001',
            'direccion' => 'Calle Principal 456',
        ]);

        // 👈 Opcional: Agregar más clientes de prueba
        Cliente::create([
            'nombre' => 'Lucio',
            'apellido' => 'Mendoza',
            'ci' => '555666777',
            'email' => 'lucio@example.com',
            'telefono' => '70000002',
            'direccion' => 'Av. Los Olivos 789',
        ]);

        Cliente::create([
            'nombre' => 'Anabel',
            'apellido' => 'Quiroz',
            'ci' => '444555666',
            'email' => 'anabel@example.com',
            'telefono' => '70000003',
            'direccion' => 'Calle Las Flores 101',
        ]);
    }
}