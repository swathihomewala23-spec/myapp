<?php
try {
    $db = new PDO('sqlite:c:\home_backendn\backend\database\database.sqlite');
    $result = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
    echo "Tables in database.sqlite:\n";
    $count = 0;
    foreach ($result as $row) {
        echo "- " . $row['name'] . "\n";
        $count++;
    }
    echo "Total tables in sqlite: $count\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
