<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;

echo "Empleados:\n";
foreach(Employee::all() as $e) {
    echo "- " . $e->name . " -> email: " . ($e->email ?? 'SIN EMAIL') . "\n";
}
