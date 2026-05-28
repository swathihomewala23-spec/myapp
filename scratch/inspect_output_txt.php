<?php
$content = file_get_contents('c:\home_backendn\backend\output.txt');
$utf8 = mb_convert_encoding($content, 'UTF-8', 'UTF-16LE');

preg_match_all('/DESCRIBE \w+:/', $utf8, $matches);
echo "Tables described in output.txt:\n";
print_r($matches[0]);
