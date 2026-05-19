<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    $tables = DB::select('SHOW TABLES');
    echo "Tables in database:\n";
    foreach ($tables as $table) {
        echo "- " . current((array)$table) . "\n";
    }

    if (Schema::hasTable('projects')) {
        echo "\nProjects table columns:\n";
        $columns = DB::select('DESCRIBE projects');
        foreach ($columns as $column) {
            echo "- {$column->Field} ({$column->Type})\n";
        }
    } else {
        echo "\nProjects table NOT FOUND.\n";
    }

    if (Schema::hasTable('properties')) {
        echo "\nProperties table columns:\n";
        $columns = DB::select('DESCRIBE properties');
        foreach ($columns as $column) {
            echo "- {$column->Field} ({$column->Type})\n";
        }
    } else {
        echo "\nProperties table NOT FOUND.\n";
    }

    echo "\nMigrations run:\n";
    if (Schema::hasTable('migrations')) {
        $migrations = DB::table('migrations')->get();
        foreach ($migrations as $m) {
            echo "- {$m->migration}\n";
        }
    } else {
        echo "- migrations table NOT FOUND.\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
