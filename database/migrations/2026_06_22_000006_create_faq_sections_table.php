<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faq_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();          // stable identifier, e.g. 'services', 'faq-basics'
            $table->string('page')->index();          // which page it renders on: 'services','training','faq','home'
            $table->string('name');                   // admin-facing label
            $table->string('kicker')->nullable();
            $table->string('title')->nullable();
            $table->string('title_em')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq_sections');
    }
};
