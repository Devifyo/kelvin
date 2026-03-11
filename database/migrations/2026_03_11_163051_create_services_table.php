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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('type', ['consulting', 'training'])->index();
            $table->text('short_description');
            
            // --- Media & SEO ---
            $table->string('featured_image')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 160)->nullable(); 
            $table->string('meta_keywords')->nullable(); // Added Meta Keywords
            
            // --- Main Body Text ---
            $table->longText('content'); 
            
            // --- Training-Specific Structured Fields ---
            $table->text('learning_objectives')->nullable(); 
            $table->text('audience')->nullable();
            $table->text('prerequisites')->nullable();
            $table->string('length')->nullable(); 
            $table->longText('topics')->nullable(); 

            // --- Status and Sorting ---
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};