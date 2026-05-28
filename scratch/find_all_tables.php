<?php
// Let's search for "create table" or "Schema::create" or "vendors" in the whole workspace to see where it comes from.
function search_dir($dir, $pattern) {
    $results = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isDir()) continue;
        $filePath = $file->getPathname();
        // Skip vendor and node_modules
        if (strpos($filePath, 'vendor') !== false || strpos($filePath, 'node_modules') !== false || strpos($filePath, '.git') !== false) {
            continue;
        }
        $content = file_get_contents($filePath);
        if (strpos($content, $pattern) !== false) {
            $results[] = $filePath;
        }
    }
    return $results;
}

echo "Searching for 'Schema::create('vendors':\n";
print_r(search_dir('c:\home_backendn\backend', "Schema::create('vendors'"));

echo "\nSearching for 'CREATE TABLE `vendors`':\n";
print_r(search_dir('c:\home_backendn\backend', "CREATE TABLE `vendors`"));

echo "\nSearching for 'CREATE TABLE' (any case):\n";
$res = search_dir('c:\home_backendn\backend', "CREATE TABLE");
if (count($res) > 10) {
    echo "Found " . count($res) . " files. Showing first 10:\n";
    print_r(array_slice($res, 0, 10));
} else {
    print_r($res);
}
