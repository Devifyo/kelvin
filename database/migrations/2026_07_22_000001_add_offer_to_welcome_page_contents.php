<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('welcome_page_contents', function (Blueprint $table) {
            // "What We Offer" / Consulting & Training Services homepage section
            // (managed via the Welcome Page CMS).
            $table->string('offer_kicker')->nullable();
            $table->string('offer_title')->nullable();
            $table->string('offer_title_em')->nullable();
            $table->text('offer_body')->nullable();
        });

        // Backfill existing content with the copy that used to be hardcoded in the view,
        // so nothing goes blank on sites that already have a welcome_page_contents row.
        DB::table('welcome_page_contents')
            ->whereNull('offer_kicker')
            ->update([
                'offer_kicker'   => 'What We Offer',
                'offer_title'    => 'Consulting &',
                'offer_title_em' => 'Training Services',
                'offer_body'     => 'We offer a variety of consulting and training services. We can work with all levels at a client, from the hands-on engineers to the C-suite. We take the time to understand the unique needs of each client, and tailor consulting services accordingly.',
            ]);
    }

    public function down(): void
    {
        Schema::table('welcome_page_contents', function (Blueprint $table) {
            $table->dropColumn([
                'offer_kicker',
                'offer_title',
                'offer_title_em',
                'offer_body',
            ]);
        });
    }
};
