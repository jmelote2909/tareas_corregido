<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Task;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;

echo "--- INICIANDO DIAGNÓSTICO DE CORREO ---\n";

// 1. Ver usuarios
echo "\n[1] Datos de Configuración Global (Ajustes):\n";
$mailer = Setting::get('mail_mailer');
$username = Setting::get('mail_username');
$password = Setting::get('mail_password');
$receiver = Setting::get('mail_receiver');
echo "Mailer: " . ($mailer ?: 'No configurado') . "\n";
echo "Username (Remitente): " . ($username ?: 'No configurado') . "\n";
echo "Password (Contraseña de aplicación): " . ($password ? 'CONFIGURADA (16 letras)' : 'No configurada') . "\n";
echo "Receiver (Destinatario de tareas completadas): " . ($receiver ?: 'No configurado') . "\n";

// 2. Ver empleados con "jesus"
echo "\n[2] Búsqueda de empleados 'jesus':\n";
$employees = \App\Models\Employee::where('name', 'like', '%jesus%')->get();
if ($employees->isEmpty()) {
    echo "¡ATENCIÓN! No se encontró ningún empleado que contenga 'jesus' en su nombre.\n";
} else {
    foreach ($employees as $emp) {
        echo "- ID: {$emp->id} | Nombre: {$emp->name} | Email: " . ($emp->email ?: 'SIN EMAIL CONFIGURADO') . "\n";
    }
}

// 3. Ver última tarea modificada
echo "\n[3] Última tarea modificada:\n";
$task = Task::orderBy('updated_at', 'desc')->first();
if (!$task) {
    echo "No hay tareas en la base de datos.\n";
    exit;
}
echo "Tarea ID: {$task->id}\n";
echo "Título: {$task->title}\n";
echo "Asignado a ID: " . ($task->assigned_to_id ?: 'Nadie') . "\n";

$emp = $task->assignedTo;
if ($emp) {
    echo "Asignado a: {$emp->name} | Email del asignado: " . ($emp->email ?: 'SIN EMAIL') . "\n";
} else {
    echo "Esta tarea no tiene a nadie asignado.\n";
}

// 4. Intentar enviar un correo de prueba usando la configuración actual
echo "\n[4] Probando envío de correo de prueba...\n";
if (!$emp || !$emp->email) {
    echo "No se puede probar el envío porque la última tarea no está asignada a nadie o no tiene email.\n";
    echo "Buscando un empleado genérico con email...\n";
    $emp = \App\Models\Employee::whereNotNull('email')->where('email', '!=', '')->first();
    if (!$emp) {
        echo "¡Error crítico! No hay ningún empleado en la base de datos con un correo configurado.\n";
        exit;
    }
    echo "Usando empleado genérico para prueba: {$emp->name} ({$emp->email})\n";
}

try {
    // Aplicamos la configuración SMTP dinámica de Ajustes
    if ($username && $password) {
        config([
            'mail.default' => $mailer ?: 'smtp',
            'mail.mailers.smtp.host' => 'smtp.gmail.com',
            'mail.mailers.smtp.port' => 587,
            'mail.mailers.smtp.encryption' => 'tls',
            'mail.mailers.smtp.username' => $username,
            'mail.mailers.smtp.password' => $password,
            'mail.from.address' => $username,
            'mail.from.name' => 'Diagnóstico Tareas App',
        ]);
        Mail::purge('smtp');
        Mail::purge();
    }

    echo "Enviando correo de prueba a: {$emp->email}...\n";
    Mail::to($emp->email)->send(new \App\Mail\TaskAssignedMail($task));
    echo "¡CORREO ENVIADO CON ÉXITO! Comprueba la bandeja de entrada y la carpeta de Spam de {$emp->email}.\n";
} catch (\Throwable $e) {
    echo "¡FALLÓ EL ENVÍO! Error:\n";
    echo $e->getMessage() . "\n";
    echo "Línea: " . $e->getLine() . " en " . $e->getFile() . "\n";
}
