<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$table = 'property_states';
$indexes = DB::select("SHOW INDEX FROM $table");
foreach ($indexes as $index) {
    echo "Index: " . $index->Key_name . ", Column: " . $index->Column_name . ", Unique: " . ($index->Non_unique == 0 ? 'YES' : 'NO') . "\n";
}
