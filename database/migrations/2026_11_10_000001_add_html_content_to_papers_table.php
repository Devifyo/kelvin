<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Resource Library — optional full-text HTML version of a document resource.
 *
 *  - html_content → the paper itself as HTML (pasted from Word in the admin),
 *                   served at /agile-hardware-papers-and-presentations/{slug}/read.
 *                   Null / blank means the resource is file-only.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->longText('html_content')->nullable()->after('long_description');
        });
    }

    public function down(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->dropColumn('html_content');
        });
    }
};
