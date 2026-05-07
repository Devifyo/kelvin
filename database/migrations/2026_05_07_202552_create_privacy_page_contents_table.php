<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('privacy_page_contents', function (Blueprint $table) {
            $table->id();

            // Header
            $table->string('header_kicker')->nullable();
            $table->string('header_h1_regular')->nullable();
            $table->string('header_h1_em')->nullable();
            $table->string('last_updated')->nullable();

            // Rich Content (TinyMCE HTML)
            $table->longText('content')->nullable();

            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('privacy_page_contents');
    }
};
