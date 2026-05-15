<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Category;

$names = ['Infraestructura', 'TI', 'Mantenimiento'];
foreach($names as $n) {
    Category::firstOrCreate(['name' => $n], ['color' => '#6366f1']);
}
echo "Categories in DB: " . Category::count() . "\n";
foreach(Category::all() as $c) {
    echo "- " . $c->name . "\n";
}
