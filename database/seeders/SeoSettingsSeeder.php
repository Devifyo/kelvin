<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * SEO-related AppSetting keys.
 *
 * Idempotent and non-destructive: an existing non-empty value is never
 * overwritten, so running this on production cannot clobber values that were
 * entered through the admin panel. Safe to re-run after every deploy.
 *
 *   php artisan db:seed --class=SeoSettingsSeeder
 */
class SeoSettingsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * key => default value.
     *
     * `seo_sameas_urls` is new (added alongside the schema `sameAs` work). It
     * holds one profile URL per line and is what tells Google and AI engines
     * WHICH Kevin Thompson this is. It ships empty on purpose — the real
     * ORCID / Google Scholar / LinkedIn / Amazon author URLs must be entered
     * in Admin → App Settings → SEO. Do not invent them here.
     */
    private const DEFAULTS = [
        'seo_sameas_urls' => '',
    ];

    public function run(): void
    {
        foreach (self::DEFAULTS as $key => $default) {
            $existing = AppSetting::where('key', $key)->first();

            if ($existing && filled($existing->value)) {
                $this->command?->line("  <fg=gray>kept</> {$key} (already set)");

                continue;
            }

            // set() also busts the per-key cache, which updateOrCreate would not.
            AppSetting::set($key, $default);
            $this->command?->line("  <fg=green>seeded</> {$key}");
        }

        $this->command?->warn(
            '  seo_sameas_urls is intentionally empty — add the real profile URLs '
            . 'in Admin → App Settings → SEO.'
        );
    }
}
