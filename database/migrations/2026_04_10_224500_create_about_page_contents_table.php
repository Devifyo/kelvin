<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_page_contents', function (Blueprint $table) {
            $table->id();
            
            // Header
            $table->string('header_kicker')->nullable();
            $table->string('header_h1_regular')->nullable();
            $table->string('header_h1_em')->nullable();
            
            // Sidebar
            $table->string('profile_image')->nullable();
            $table->string('sidebar_kicker')->nullable();
            $table->json('education_list')->nullable();
            
            // Content
            $table->text('intro_text')->nullable();
            $table->string('section_1_h2_regular')->nullable();
            $table->string('section_1_h2_em')->nullable();
            $table->text('section_1_p1')->nullable();
            $table->text('section_1_p2')->nullable();
            $table->text('highlight_quote')->nullable();
            $table->text('section_1_p3')->nullable();
            
            $table->string('section_2_h2_regular')->nullable();
            $table->string('section_2_h2_em')->nullable();
            $table->text('section_2_p1')->nullable();
            $table->text('section_2_p2')->nullable();
            $table->text('section_2_p3')->nullable();
            
            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_page_contents');
    }
};
