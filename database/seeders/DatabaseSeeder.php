<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Limpiar tablas para evitar duplicados
        User::query()->delete();
        Employee::query()->delete();
        Task::query()->delete();
        Project::query()->delete();

        // 2. Crear Admin Principal
        User::create([
            'username' => 'admin',
            'name' => 'Admin Sistema',
            'email' => 'admin@empresa.com',
            'password' => 'Cima1100',
            'role' => 'admin',
            'department' => 'Sistemas',
            'position' => 'Director IT',
        ]);
    }
}
