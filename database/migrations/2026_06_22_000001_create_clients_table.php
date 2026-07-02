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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->comment('Path to the client logo on the public disk');
            $table->string('website_url')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false)->index()->comment('Shown in the homepage "Trusted By" section');
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
