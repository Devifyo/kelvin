<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45);
            $table->string('country')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('device')->nullable();       // desktop | mobile | tablet
            $table->string('page')->nullable();         // request path
            $table->string('referrer')->nullable();
            $table->unsignedSmallInteger('session_duration')->nullable(); // seconds
            $table->boolean('is_bounce')->default(false);
            $table->boolean('is_new_visitor')->default(true);
            $table->timestamps();

            $table->index('country_code');
            $table->index('created_at');
            $table->index('device');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_logs');
    }
};
