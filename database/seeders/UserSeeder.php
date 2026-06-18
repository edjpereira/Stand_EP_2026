<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador (Hierarquia Alta)
        User::factory()->create([
            'name' => 'Eduardo Pereira (Admin)',
            'email' => 'edjpereira@posteo.pt',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        // Funcionário Padrão (Hierarquia Base)
        User::factory()->create([
            'name' => 'Funcionário Stand',
            'email' => 'vendedor@coiso.pt',
            'password' => bcrypt('password123'),
            'role' => 'employee',
        ]);
    }
}
