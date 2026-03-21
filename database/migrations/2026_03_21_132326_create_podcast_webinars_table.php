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
        Schema::create('podcast_webinars', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // 'podcast', 'webinar', 'interview'
            $table->string('platform')->nullable(); // e.g., 'YouTube', 'Spotify'
            $table->string('url'); // The actual link
            $table->text('description')->nullable();
            $table->string('thumbnail_image')->nullable();
            $table->date('published_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcast_webinars');
    }
};
