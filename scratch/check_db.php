<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = ['categories', 'property_categories', 'property_countries', 'property_states', 'property_cities', 'property_places'];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "Table '$table' EXISTS.\n";
        $columns = Schema::getColumnListing($table);
        echo "Columns: " . implode(', ', $columns) . "\n";
    } else {
        echo "Table '$table' DOES NOT EXIST.\n";
    }
}
