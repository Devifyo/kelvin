<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('welcome_page_contents', function (Blueprint $table) {
            // Portrait shown in the homepage "Our Principal" bio header
            $table->string('principal_portrait_image')->nullable()->after('principal_p3');
        });
    }

    public function down(): void
    {
        Schema::table('welcome_page_contents', function (Blueprint $table) {
            $table->dropColumn('principal_portrait_image');
        });
    }
};
