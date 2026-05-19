<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admin = App\Models\Admin::create([
    'name' => 'Admin',
    'email' => 'admin@admin.com',
    'username' => 'admin@admin.com',
    'password' => 'admin1234',
]);

echo "Admin created successfully!\n";
echo "ID: " . $admin->id . "\n";
echo "Username: admin@admin.com\n";
echo "Password: admin1234\n";
