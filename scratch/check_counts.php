<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Countries: " . App\Models\Country::count() . "\n";
echo "States: " . App\Models\State::count() . "\n";
echo "Cities: " . App\Models\City::count() . "\n";
echo "Categories: " . App\Models\Category::count() . "\n";
