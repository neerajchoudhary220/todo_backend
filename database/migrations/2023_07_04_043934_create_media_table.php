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
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('slug')->unique();
            $table->string('path')->comment('Medial location on drive');
            $table->string('name');
            $table->string('mime_type');
            $table->string('extension');
            $table->unsignedInteger('size')->comment("media size in bytes (max 4.294967295 Gigabytes)");
            $table->json('tags')->nullable()->comment("eg: profile image, user post...");
            $table->json('thumbnail')->nullable()->comment("contains thumbnails or placeholder");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
