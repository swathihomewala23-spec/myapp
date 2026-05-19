<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = DB::select('SHOW TABLES');
foreach ($tables as $t) {
    $vals = array_values((array)$t);
    echo $vals[0] . PHP_EOL;
}
