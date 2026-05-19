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
        Schema::create('page_pinned_projects', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug')->comment('e.g. chennai-page-pinning');
            $table->unsignedInteger('display_order')->default(1)->comment('1=first, 2=second, etc.');
            $table->unsignedBigInteger('property_id');
            $table->string('property_name')->nullable();
            $table->string('property_location')->nullable();
            $table->string('property_builder')->nullable();
            $table->timestamps();

            $table->unique(['page_slug', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_pinned_projects');
    }
};
