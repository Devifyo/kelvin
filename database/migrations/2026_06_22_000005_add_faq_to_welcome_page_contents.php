<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('welcome_page_contents', function (Blueprint $table) {
            // Homepage FAQ section (also powers FAQPage JSON-LD for SEO/AEO)
            $table->boolean('faq_enabled')->default(true);
            $table->string('faq_kicker')->nullable();
            $table->string('faq_title')->nullable();
            $table->string('faq_title_em')->nullable();
            $table->json('faq_items')->nullable(); // [{q, a}, ...]
        });
    }

    public function down(): void
    {
        Schema::table('welcome_page_contents', function (Blueprint $table) {
            $table->dropColumn(['faq_enabled', 'faq_kicker', 'faq_title', 'faq_title_em', 'faq_items']);
        });
    }
};
