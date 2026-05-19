<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('our_partners')) {
            return;
        }

        Schema::create('our_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('projects')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('our_partners');
    }
};
