<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$drivers = \App\Models\User::where('role', 'driver')->get(['id', 'name', 'current_lat', 'current_lng']);
foreach($drivers as $d) {
    echo "ID: {$d->id} | Name: {$d->name} | Lat: {$d->current_lat} | Lng: {$d->current_lng}\n";
}
