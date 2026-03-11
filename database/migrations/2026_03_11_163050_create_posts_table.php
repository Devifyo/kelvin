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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            
            // --- Media & File Attachments ---
            $table->string('featured_image')->nullable()->comment('Thumbnail for blog index page');
            $table->string('attachment')->nullable()->comment('Path to the attached file');
            $table->string('attachment_mime_type')->nullable()->comment('e.g., application/pdf, application/msword');
            
            // --- Meta & Excerpts ---
            $table->text('excerpt')->nullable()->comment('Short summary for the blog index page');
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 160)->nullable();
            $table->string('meta_keywords')->nullable();

            // --- Main Content ---
            $table->longText('content')->comment('Text Editor HTML (inline images live inside this HTML)'); 
            
            // --- Publishing Controls ---
            $table->enum('status', ['draft', 'published'])->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};