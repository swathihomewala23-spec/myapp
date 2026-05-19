<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_requests', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->text('reason'); // "Member Request Reason"
            $table->string('status')->default('pending'); // "Request Status"
            $table->timestamps(); // Provides created_at for "Date Time"
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_requests');
    }
};