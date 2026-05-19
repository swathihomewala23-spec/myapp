<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to bypass Laravel's type mapping issues and FK compatibility checks for simple nullability
        // But wrap in Schema::hasColumn checks to avoid failure if columns don't exist (e.g. fresh install)
        
        if (Schema::hasColumn('admins', 'vendor_request_message')) {
            DB::statement("ALTER TABLE admins MODIFY vendor_request_message TEXT NULL");
        }
        
        if (Schema::hasColumn('admins', 'image')) {
            DB::statement("ALTER TABLE admins MODIFY image VARCHAR(255) NULL");
        }
        
        if (Schema::hasColumn('admins', 'first_name')) {
            DB::statement("ALTER TABLE admins MODIFY first_name VARCHAR(255) NULL");
        }
        
        if (Schema::hasColumn('admins', 'last_name')) {
            DB::statement("ALTER TABLE admins MODIFY last_name VARCHAR(255) NULL");
        }
        
        if (Schema::hasColumn('admins', 'address')) {
            DB::statement("ALTER TABLE admins MODIFY address TEXT NULL");
        }
        
        if (Schema::hasColumn('admins', 'details')) {
            DB::statement("ALTER TABLE admins MODIFY details TEXT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
