<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$employees = \App\Models\Employee::all();
foreach ($employees as $emp) {
    echo "ID: {$emp->id} | Name: {$emp->name} | Email: {$emp->email}\n";
}
