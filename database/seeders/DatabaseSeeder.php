<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario admin
        User::create([
            'name' => 'Administrador',
            'ci' => '123456789',                    
            'email' => 'admin@electrobol.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'is_active' => true
        ]);
        
        // Crear usuario técnico
        User::create([
            'name' => 'Carlos Mendoza',             
            'ci' => '987654321',                   
            'email' => 'carlos@electrobol.com',    
            'password' => bcrypt('tecnico123'),
            'role' => 'user',
            'is_active' => true
        ]);
        
        // 👈 Opcional: Crear un usuario técnico adicional
        User::create([
            'name' => 'Ana Quispe',
            'ci' => '555666777',
            'email' => 'ana@electrobol.com',
            'password' => bcrypt('tecnico123'),
            'role' => 'user',
            'is_active' => true
        ]);
        
        // 👈 Opcional: Crear un usuario regular
        User::create([
            'name' => 'Usuario Regular',
            'ci' => '444555666',
            'email' => 'usuario@electrobol.com',
            'password' => bcrypt('usuario123'),
            'role' => 'user',
            'is_active' => true
        ]);
        
        // Ejecutar seeders adicionales
        $this->call([
            TecnicoSeeder::class,
            RepuestoSeeder::class,
            ClienteSeeder::class,
        ]);
    }
}