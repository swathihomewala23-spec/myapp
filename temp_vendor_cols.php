<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check vendors with photos
try {
    $vendors = Illuminate\Support\Facades\DB::table('vendors')->whereNotNull('photo')->get();
    echo "Vendors with photo: " . $vendors->count() . "\n";
    foreach ($vendors as $v) {
        echo "  ID: {$v->id}, photo: {$v->photo}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Check all vendors
try {
    $all = Illuminate\Support\Facades\DB::table('vendors')->get();
    echo "\nAll vendors:\n";
    foreach ($all as $v) {
        echo "  ID: {$v->id}, name: {$v->first_name} {$v->last_name}, photo: " . ($v->photo ?? 'NULL') . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Check the .env for DB connection details
$env = file_get_contents('.env');
preg_match('/DB_DATABASE=(.+)/', $env, $m);
echo "\nDB: " . ($m[1] ?? 'unknown') . "\n";

// Check if there's a separate connection for vendors
preg_match('/DB_CONNECTION=(.+)/', $env, $m2);
echo "Connection: " . ($m2[1] ?? 'unknown') . "\n";
