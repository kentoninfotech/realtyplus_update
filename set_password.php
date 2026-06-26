<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$user = App\Models\User::find(2);
if ($user) {
    $user->password = bcrypt('password123');
    $user->save();
    echo 'Password updated for ' . $user->email . PHP_EOL;
} else {
    echo 'User not found';
}
