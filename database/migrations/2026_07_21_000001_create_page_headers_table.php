<?php

use App\Models\PageHeader;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_headers', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();
            $table->string('kicker')->nullable();
            $table->string('title_regular')->nullable();
            $table->string('title_em')->nullable();
            $table->text('subtitle')->nullable();
            $table->timestamps();
        });

        $this->seedFromCurrentContent();
    }

    public function down(): void
    {
        Schema::dropIfExists('page_headers');
    }

    /**
     * Seed each page with the copy that is live today, so switching the blades
     * over to <x-page-header /> is a no-op visually. The About page header was
     * previously stored on about_page_contents — carry those values across.
     */
    private function seedFromCurrentContent(): void
    {
        $about = Schema::hasTable('about_page_contents')
            ? DB::table('about_page_contents')->first()
            : null;

        $rows = [];

        foreach (PageHeader::PAGES as $pageKey => $config) {
            $defaults = $config['defaults'];

            if ($pageKey === 'about' && $about) {
                $defaults['kicker']        = $about->header_kicker ?: $defaults['kicker'];
                $defaults['title_regular'] = $about->header_h1_regular ?: $defaults['title_regular'];
                $defaults['title_em']      = $about->header_h1_em ?: $defaults['title_em'];
            }

            $rows[] = array_merge($defaults, [
                'page_key'   => $pageKey,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('page_headers')->insert($rows);
    }
};
