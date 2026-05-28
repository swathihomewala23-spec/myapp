<?php
$logPath = dirname(__DIR__) . '/storage/logs/laravel.log';
if (!file_exists($logPath)) {
    die("Log file not found at: $logPath\n");
}

$lines = file($logPath);
$lastLines = array_slice($lines, -150);

foreach ($lastLines as $line) {
    // Only show lines that don't look like stack trace items (e.g. #12 ...)
    if (!preg_match('/^\s*#\d+/', $line)) {
        echo $line;
    }
}
