<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $table) {
                $table->id();
                $table->string('original_name')->nullable();
                $table->string('unique_name')->nullable();
                $table->string('file_path');
                $table->string('link')->nullable();
                $table->string('type')->default('slider');
                $table->boolean('status')->default(true);
                $table->timestamps();
            });

            return;
        }

        Schema::table('banners', function (Blueprint $table) {
            if (! Schema::hasColumn('banners', 'original_name')) {
                $table->string('original_name')->nullable()->after('id');
            }

            if (! Schema::hasColumn('banners', 'unique_name')) {
                $table->string('unique_name')->nullable()->after(
                    Schema::hasColumn('banners', 'original_name') ? 'original_name' : 'id'
                );
            }

            if (! Schema::hasColumn('banners', 'file_path') && Schema::hasColumn('banners', 'image')) {
                $table->string('file_path')->nullable()->after('image');
            }

            if (! Schema::hasColumn('banners', 'image') && Schema::hasColumn('banners', 'file_path')) {
                $table->string('image')->nullable()->after('file_path');
            }

            if (! Schema::hasColumn('banners', 'type')) {
                $table->string('type')->default('slider')->after('id');
            }

            if (! Schema::hasColumn('banners', 'link')) {
                $table->string('link')->nullable();
            }

            if (! Schema::hasColumn('banners', 'status')) {
                $table->boolean('status')->default(true)->after(
                    Schema::hasColumn('banners', 'link') ? 'link' : (Schema::hasColumn('banners', 'type') ? 'type' : 'id')
                );
            }

            if (! Schema::hasColumn('banners', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('banners', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Intentionally left non-destructive because this migration may upgrade
        // an existing banners table in place.
    }
};
