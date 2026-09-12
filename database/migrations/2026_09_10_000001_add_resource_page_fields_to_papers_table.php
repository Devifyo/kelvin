<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Resource Library — every paper becomes a dedicated, SEO-editable resource page.
 *
 *  - slug                → public URL segment (/agile-hardware-papers-and-presentations/{slug})
 *  - resource_type       → document (uploaded file) | book (Amazon link) | link (website)
 *  - external_url        → destination for book / link resources
 *  - cta_label           → optional override for the derived button label
 *  - is_featured         → drives the default "Featured" tab on the listing
 *  - long_description    → rich text body of the resource page (falls back to description)
 *  - featured_image      → cover / OG image
 *  - meta_*              → per-page SEO
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->string('resource_type', 20)->default('document')->after('sub_category');
            $table->string('external_url')->nullable()->after('file_path');
            $table->string('cta_label')->nullable()->after('external_url');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->longText('long_description')->nullable()->after('description');
            $table->string('featured_image')->nullable()->after('long_description');
            $table->string('meta_title')->nullable()->after('featured_image');
            $table->string('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords')->nullable()->after('meta_description');
        });

        $this->backfillSlugs();

        Schema::table('papers', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unique('slug');
        });

        $this->renameHeroCopyIfStillDefault();
    }

    public function down(): void
    {
        Schema::table('papers', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn([
                'slug', 'resource_type', 'external_url', 'cta_label', 'is_featured',
                'long_description', 'featured_image', 'meta_title', 'meta_description', 'meta_keywords',
            ]);
        });
    }

    /** Every existing paper gets a unique slug derived from its title. */
    private function backfillSlugs(): void
    {
        $taken = [];

        DB::table('papers')->orderBy('id')->get(['id', 'title'])->each(function ($paper) use (&$taken) {
            $base = Str::slug($paper->title) ?: 'resource-' . $paper->id;
            $slug = $base;
            $i = 2;
            while (in_array($slug, $taken, true)) {
                $slug = "{$base}-{$i}";
                $i++;
            }
            $taken[] = $slug;

            DB::table('papers')->where('id', $paper->id)->update(['slug' => $slug]);
        });
    }

    /**
     * The listing hero is client-editable (Admin → Page Headers). Only rename it
     * when it still carries the original default so a custom edit is never lost.
     */
    private function renameHeroCopyIfStillDefault(): void
    {
        if (! Schema::hasTable('page_headers')) {
            return;
        }

        DB::table('page_headers')
            ->where('page_key', 'papers')
            ->where('title_regular', 'Papers &')
            ->where('title_em', 'Presentations')
            ->update([
                'title_regular' => 'Resource',
                'title_em'      => 'Library',
                'updated_at'    => now(),
            ]);
    }
};
