<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_logo')->nullable();
            $table->string('website_favicon')->nullable();
            $table->string('website_title')->nullable();
            $table->text('website_description')->nullable();
            $table->string('email_address')->nullable();
            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();
            $table->boolean('enable_smtp')->default(false);
            $table->string('smtp_host')->nullable();
            $table->string('smtp_port')->nullable();
            $table->string('smtp_encryption')->nullable();
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();
            $table->string('from_mail')->nullable();
            $table->string('from_name')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
