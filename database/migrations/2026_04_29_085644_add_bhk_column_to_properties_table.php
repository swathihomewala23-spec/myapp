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
        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->text('bhk')->nullable()->after('possession_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->dropColumn('bhk');
            });
        }
    }
};
