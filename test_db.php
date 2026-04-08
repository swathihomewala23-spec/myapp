<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = Illuminate\Support\Facades\DB::select('SHOW TABLES');
$names = array_map(fn($t) => array_values((array)$t)[0], $tables);
file_put_contents('tables.json', json_encode($names, JSON_PRETTY_PRINT));
echo "Done. Written to tables.json\n";
