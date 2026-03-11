<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\User::where('role', 'driver')->update([
    'current_lat' => 23.0125,
    'current_lng' => 72.5810,
]);
echo "Simulated drivers moving to Central Ahmedabad.\n";
