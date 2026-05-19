<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('amenities', function (Blueprint $table) {
            $table->string('color')->nullable()->after('icon')->comment('Hex code for the icon color');
        });
    }

    public function down(): void
    {
        Schema::table('amenities', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};