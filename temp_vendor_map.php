<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$vendors = Illuminate\Support\Facades\DB::table('vendors')
    ->leftJoin('vendor_infos', function ($join) {
        $join->on('vendors.id', '=', 'vendor_infos.vendor_id')
             ->where('vendor_infos.language_id', 20);
    })
    ->select('vendors.id', 'vendors.photo', 'vendors.first_name', 'vendors.last_name', 'vendor_infos.name as display_name')
    ->orderBy('vendors.id')
    ->get();

foreach ($vendors as $vendor) {
    $name = trim((string) ($vendor->display_name ?? trim(($vendor->first_name ?? '') . ' ' . ($vendor->last_name ?? ''))));
    if ($name === '') {
        $name = 'Unnamed Vendor';
    }

    echo $vendor->id . PHP_EOL;
    echo 'NAME: ' . $name . PHP_EOL;
    echo 'PHOTO: ' . ($vendor->photo ?? '') . PHP_EOL;
    echo PHP_EOL;
}
?>
