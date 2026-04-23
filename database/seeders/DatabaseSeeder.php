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
        $adminUser = User::create([
            'username' => 'admin',
            'name' => 'Admin Sistema',
            'email' => 'admin@empresa.com',
            'password' => 'Cima1100',
            'role' => 'admin',
            'department' => 'Sistemas',
            'position' => 'Director IT',
        ]);

        // 3. Crear Empleados con sus Usuarios vinculados
        $employeesData = [
            [
                'name' => 'Jesus Perez',
                'username' => 'jesus.perez',
                'email' => 'jesus@empresa.com',
                'role' => 'admin',
                'color' => '#6366f1',
                'position' => 'Jefe de Equipo',
                'dept' => 'Infraestructura'
            ],
            [
                'name' => 'Maria Garcia',
                'username' => 'maria.garcia',
                'email' => 'maria@empresa.com',
                'role' => 'employee',
                'color' => '#ec4899',
                'position' => 'Técnico Senior',
                'dept' => 'Sistemas'
            ],
            [
                'name' => 'Carlos Rodriguez',
                'username' => 'carlos.rod',
                'email' => 'carlos@empresa.com',
                'role' => 'employee',
                'color' => '#10b981',
                'position' => 'Soporte Nivel 1',
                'dept' => 'Mantenimiento'
            ],
            [
                'name' => 'Ana Martinez',
                'username' => 'ana.mtz',
                'email' => 'ana@empresa.com',
                'role' => 'employee',
                'color' => '#f59e0b',
                'position' => 'Analista TI',
                'dept' => 'Sistemas'
            ],
        ];

        $employees = [];
        foreach ($employeesData as $data) {
            $user = User::create([
                'username' => $data['username'],
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => 'password123',
                'role' => $data['role'],
                'department' => $data['dept'],
                'position' => $data['position'],
            ]);

            $employees[] = Employee::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'] === 'admin' ? 'Administrador' : 'Empleado',
                'color' => $data['color'],
                'is_active' => true,
            ]);
        }

        // 4. Crear Proyectos
        $projects = [
            Project::create([
                'name' => 'Migración Cloud 2024',
                'description' => 'Mover todos los servidores locales a AWS.',
                'status' => 'En Progreso',
                'priority' => 'Alta',
                'start_date' => now()->subMonths(1),
                'budget' => 50000,
            ]),
            Project::create([
                'name' => 'Auditoría de Seguridad',
                'description' => 'Revisión anual de protocolos y firewall.',
                'status' => 'Planificación',
                'priority' => 'Crítica',
                'start_date' => now()->addDays(5),
            ]),
            Project::create([
                'name' => 'Renovación de Hardware',
                'description' => 'Sustitución de portátiles obsoletos.',
                'status' => 'Completada',
                'priority' => 'Media',
                'start_date' => now()->subMonths(3),
                'end_date' => now()->subDays(10),
                'budget' => 12000,
            ]),
        ];

        // 5. Crear Tareas variadas
        $titles = [
            'Configurar backups', 'Revisar logs', 'Actualizar antivirus', 
            'Reparar switch planta 2', 'Reunión semanal', 'Documentar procesos',
            'Backup base de datos', 'Cambio de contraseñas', 'Soporte usuario 404'
        ];

        foreach ($employees as $index => $emp) {
            // 2 tareas para cada empleado
            for ($i = 0; $i < 3; $i++) {
                $status = ['pendiente', 'en_proceso', 'completada'][rand(0, 2)];
                Task::create([
                    'title' => $titles[array_rand($titles)] . ' - ' . ($i + 1),
                    'description' => 'Descripción detallada para la tarea de prueba ' . ($i + 1),
                    'status' => $status,
                    'priority' => ['baja', 'media', 'alta', 'urgente'][rand(0, 3)],
                    'requested_by_id' => $adminUser->id,
                    'assigned_to_id' => $emp->id,
                    'due_date' => now()->addDays(rand(-5, 10)),
                ]);
            }
        }
    }
}
