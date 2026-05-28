<?php
$logPath = __DIR__ . '/../storage/logs/laravel.log';
if (!file_exists($logPath)) {
    die("Log file not found.\n");
}

$content = file_get_contents($logPath);
$pos = strrpos($content, 'production.ERROR: SQLSTATE[42S02]');
if ($pos === false) {
    $pos = strrpos($content, 'local.ERROR: SQLSTATE[42S02]');
}

if ($pos !== false) {
    echo "Found error at position $pos:\n";
    $lines = explode("\n", substr($content, $pos, 15000));
    // Print first 80 lines of the match
    for ($i = 0; $i < min(80, count($lines)); $i++) {
        echo $lines[$i] . "\n";
    }
} else {
    echo "Error pattern not found in log.\n";
}
