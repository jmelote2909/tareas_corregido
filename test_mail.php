<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $task = App\Models\Task::first();
    $employee = App\Models\Employee::whereNotNull('email')->first();
    if ($task && $employee) {
        // Change it to force the updated event to fire
        $task->assigned_to_id = null;
        $task->save();
        
        $task->assigned_to_id = $employee->id;
        $task->save();
        echo "OK";
    } else {
        echo "No task or employee found";
    }
} catch (\Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
