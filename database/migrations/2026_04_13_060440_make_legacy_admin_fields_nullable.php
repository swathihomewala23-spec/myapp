<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $columns = [
                'role_id', 'first_name', 'last_name', 'image', 'address', 
                'details', 'vendor_request_message', 'status', 
                'show_email_addresss', 'show_phone_number', 'show_contact_form', 
                'is_admin', 'country', 'city', 'state', 'zip_code', 'phone'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('admins', $column)) {
                    $table->string($column)->nullable()->change();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to revert nullability without knowing exact previous states
    }
};
