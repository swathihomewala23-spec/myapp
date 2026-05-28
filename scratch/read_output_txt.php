<?php
$content = file_get_contents('c:\home_backendn\backend\output.txt');
// Convert from UTF-16LE to UTF-8
$utf8 = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');
$lines = explode("\n", $utf8);
echo "Total lines: " . count($lines) . "\n";
echo "First 100 lines:\n";
for ($i = 0; $i < min(100, count($lines)); $i++) {
    echo "$i: " . $lines[$i] . "\n";
}
