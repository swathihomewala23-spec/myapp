<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('admins', 'contact_number')) {
                $table->string('contact_number', 20)->nullable()->after('email');
            }
            $table->string('username')->nullable()->change();
        });

        // Populate 'name' from first_name and last_name if they exist
        if (Schema::hasColumns('admins', ['first_name', 'last_name', 'name'])) {
            DB::table('admins')->whereNull('name')->update([
                'name' => DB::raw("TRIM(CONCAT(IFNULL(first_name, ''), ' ', IFNULL(last_name, '')))")
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['name', 'contact_number']);
        });
    }
};
