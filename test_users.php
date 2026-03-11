<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = App\Models\User::all(['id', 'email', 'phone', 'email_verified_at']);
foreach($users as $user) {
    echo "ID: {$user->id} | Email: {$user->email} | Verification: {$user->email_verified_at}\n";
}
