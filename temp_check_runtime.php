<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "default=" . config('database.default') . "\n";
echo "driver=" . config('database.connections.' . config('database.default') . '.driver') . "\n";
echo "database=" . config('database.connections.' . config('database.default') . '.database') . "\n";
echo "has_properties=" . (Illuminate\Support\Facades\Schema::hasTable('properties') ? 'yes' : 'no') . "\n";
if (Illuminate\Support\Facades\Schema::hasTable('properties')) {
    echo "properties_count=" . Illuminate\Support\Facades\DB::table('properties')->count() . "\n";
}
