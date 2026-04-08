<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (! Schema::hasTable('properties')) {
  echo "NO_TABLE\n";
  exit;
}

$query = DB::table('properties')
    ->select(
        'properties.id',
        'properties.property_name',
        'properties.type',
        'properties.city',
        'properties.status',
        'properties.approve_status',
        'properties.new_launched',
        'properties.elite_project',
        'properties.created_at'
    )
    ->orderByDesc('properties.id');

if (Schema::hasTable('property_contents')) {
    $query->leftJoin('property_contents', 'property_contents.property_id', '=', 'properties.id')
        ->addSelect('property_contents.title as content_title');
}

if (Schema::hasTable('vendors')) {
    $query->leftJoin('vendors', 'vendors.id', '=', 'properties.vendor_id')
        ->addSelect('vendors.first_name as vendor_first_name', 'vendors.last_name as vendor_last_name');
}

if (Schema::hasTable('agents')) {
    $query->leftJoin('agents', 'agents.id', '=', 'properties.agent_id')
        ->addSelect('agents.username as agent_username');
}

$rows = $query->get()->map(function ($property) {
    $title = $property->content_title ?? $property->property_name;
    $vendorName = trim(($property->vendor_first_name ?? '') . ' ' . ($property->vendor_last_name ?? ''));
    $postBy = $vendorName !== '' ? $vendorName : ($property->agent_username ?? 'Admin');

    $property->title = $title ?: 'Untitled Property';
    $property->post_by = $postBy;

    return $property;
});

echo "COUNT=" . $rows->count() . "\n";
$first = $rows->first();
if ($first) {
  echo "FIRST_TITLE=" . $first->title . "\n";
}
