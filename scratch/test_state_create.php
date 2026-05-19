<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\State;
use App\Models\Country;

try {
    $country = Country::first();
    if (!$country) {
        die("No country found to test with.\n");
    }
    echo "Testing with country ID: " . $country->id . "\n";
    
    $state = State::create([
        'name' => 'Test State ' . time(),
        'country_id' => $country->id,
    ]);
    echo "State created successfully! ID: " . $state->id . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "SQL: " . ($e instanceof \Illuminate\Database\QueryException ? $e->getSql() : 'N/A') . "\n";
}
