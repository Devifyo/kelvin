<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('welcome_page_contents', function (Blueprint $table) {
            // "Trusted By" / Client Showcase homepage section (managed via the Welcome Page CMS)
            $table->boolean('trusted_enabled')->default(true);
            $table->string('trusted_kicker')->nullable();
            $table->string('trusted_title')->nullable();
            $table->string('trusted_title_em')->nullable();
            $table->string('trusted_count_label')->nullable();
            $table->string('trusted_cta_text')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('welcome_page_contents', function (Blueprint $table) {
            $table->dropColumn([
                'trusted_enabled',
                'trusted_kicker',
                'trusted_title',
                'trusted_title_em',
                'trusted_count_label',
                'trusted_cta_text',
            ]);
        });
    }
};
