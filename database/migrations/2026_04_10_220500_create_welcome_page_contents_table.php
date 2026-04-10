<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('welcome_page_contents', function (Blueprint $table) {
            $table->id();
            
            // Hero Section
            $table->string('hero_kicker')->nullable();
            $table->string('hero_h1_em')->nullable();
            $table->string('hero_h1_strong')->nullable();
            $table->text('hero_p1')->nullable();
            $table->text('hero_p2')->nullable();
            $table->string('hero_cta_primary_text')->nullable();
            $table->string('hero_cta_primary_link')->nullable();
            $table->string('hero_cta_secondary_text')->nullable();
            $table->string('hero_cta_secondary_link')->nullable();
            
            // Pain List Section
            $table->string('pain_title')->nullable();
            $table->json('pain_list')->nullable();
            $table->string('pain_footer')->nullable();
            
            // Principal Section
            $table->string('principal_kicker')->nullable();
            $table->string('principal_h2_name')->nullable();
            $table->string('principal_h2_em')->nullable();
            $table->text('principal_p1')->nullable();
            $table->text('principal_p2')->nullable();
            $table->text('principal_p3')->nullable();
            
            // Book info
            $table->string('principal_book_image')->nullable();
            $table->string('principal_book_title')->nullable();
            $table->text('principal_book_desc')->nullable();
            $table->string('principal_book_url')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('welcome_page_contents');
    }
};
