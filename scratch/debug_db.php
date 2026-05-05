<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee;
use App\Models\Team;

echo "Teams:\n";
foreach(Team::all() as $t) {
    echo "- " . $t->name . " (ID: " . $t->id . ")\n";
}

echo "\nEmployees:\n";
foreach(Employee::all() as $e) {
    echo "- " . $e->name . " (Team: " . ($e->team?->name ?? 'None') . ", TeamID: " . ($e->team_id ?? 'NULL') . ")\n";
}
