<?php
$content = file_get_contents('c:\home_backendn\backend\temp_diff.txt');
$lines = explode("\n", $content);
echo "Total lines: " . count($lines) . "\n";
echo "First 100 lines:\n";
for ($i = 0; $i < min(100, count($lines)); $i++) {
    echo "$i: " . $lines[$i] . "\n";
}
