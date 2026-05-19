<?php
/**
 * Sync migration state: mark migrations as "Ran" for tables that already exist,
 * and actually run migrations for tables that don't exist yet.
 */
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Migrations whose tables/alters are already applied in the DB
// (table already exists, so we just mark them complete without running)
$alreadyDone = [
    '0001_01_01_000000_create_users_table',
    '2026_03_30_103000_add_admin_fields_to_users_table',
    '2026_04_01_063350_create_amenities_table',
    '2026_04_01_093515_add_type_to_amenities_table',
    '2026_04_01_101021_change_status_to_string_in_amenities_table',
    '2026_04_02_120100_add_status_to_users_table',
    '2026_04_02_123500_remove_type_from_amenities_table',
    '2026_04_11_071250_create_property_enquiries_table',
    '2026_04_13_040954_create_admins_table',
    '2026_04_13_055609_add_missing_fields_to_admins_table',
    '2026_04_13_060325_make_username_nullable_on_admins_table',
    '2026_04_13_060440_make_legacy_admin_fields_nullable',
    '2026_04_13_060530_fix_legacy_admin_nullability',
    '2026_04_16_055249_add_profile_fields_to_admins_table',
    '2026_04_17_000002_create_or_upgrade_banners_table',
    '2026_04_17_000003_create_our_partners_table',
    '2026_04_17_000004_create_interior_designs_table',
];

$batch = 17;
$inserted = 0;

foreach ($alreadyDone as $migration) {
    $exists = DB::table('migrations')->where('migration', $migration)->exists();
    if (!$exists) {
        DB::table('migrations')->insert([
            'migration' => $migration,
            'batch'     => $batch,
        ]);
        echo "Marked as ran: {$migration}\n";
        $inserted++;
    } else {
        echo "Already tracked: {$migration}\n";
    }
}

echo "\nDone. Marked {$inserted} migrations as completed.\n";
echo "Run 'php artisan migrate' next to create the remaining missing tables.\n";
