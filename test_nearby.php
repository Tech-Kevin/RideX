<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Manually test the controller logic 
$drivers = \App\Models\User::where('role', 'driver')
    ->whereNotNull('current_lat')
    ->whereNotNull('current_lng')
    ->get(['id', 'current_lat', 'current_lng']);

echo json_encode(['drivers' => $drivers], JSON_PRETTY_PRINT);
