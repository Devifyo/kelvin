<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitor_logs', function (Blueprint $table) {
            // Stable per-browser identity (cookie based). Lets us recognise the
            // same browser across IP changes and count returning visitors.
            $table->uuid('visitor_id')->nullable()->after('ip_address');

            // Groups page views into a single "visit". Minted on first hit and
            // reused while the visitor stays active (within the session timeout).
            $table->uuid('session_id')->nullable()->after('visitor_id');

            // Traffic classification — humans vs filtered bots/crawlers.
            $table->boolean('is_bot')->default(false)->after('is_new_visitor');
            $table->string('bot_reason', 40)->nullable()->after('is_bot');

            $table->index('visitor_id');
            $table->index('session_id');
            $table->index('is_bot');
        });

        // ── Backfill historical data ──────────────────────────────────────
        // Rows with no detectable browser were almost always headless bots /
        // scanners (e.g. Shodan) sending no User-Agent. Flag them so existing
        // analytics immediately reflect human-only traffic.
        DB::table('visitor_logs')
            ->where(function ($q) {
                $q->whereNull('browser')->orWhere('browser', '');
            })
            ->update(['is_bot' => true, 'bot_reason' => 'empty-user-agent']);
    }

    public function down(): void
    {
        Schema::table('visitor_logs', function (Blueprint $table) {
            $table->dropIndex(['visitor_id']);
            $table->dropIndex(['session_id']);
            $table->dropIndex(['is_bot']);
            $table->dropColumn(['visitor_id', 'session_id', 'is_bot', 'bot_reason']);
        });
    }
};
