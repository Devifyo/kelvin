<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('podcast_webinars', function (Blueprint $table) {
            // url is now optional — user may upload a video instead
            $table->string('url')->nullable()->change();
            // path to the uploaded video file in public storage
            $table->string('video_path')->nullable()->after('url');
        });
    }

    public function down(): void
    {
        Schema::table('podcast_webinars', function (Blueprint $table) {
            $table->string('url')->nullable(false)->change();
            $table->dropColumn('video_path');
        });
    }
};
