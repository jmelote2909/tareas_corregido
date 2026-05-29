<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Authenticate as the first admin
    $admin = App\Models\User::where('role', 'admin')->first();
    auth()->login($admin);
    
    echo "Logged in as: " . auth()->user()->email . "\n";
    echo "Has email_password? " . (auth()->user()->email_password ? 'Yes' : 'No') . "\n";

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
