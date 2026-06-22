<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Services\ImageOptimizer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ClientSeeder extends Seeder
{
    /**
     * Maps logo filename (without extension) → polished display name.
     * Anything not listed falls back to a humanised version of the filename.
     */
    private const NAMES = [
        'AlfaInsurance' => 'Alfa Insurance',
        'AppliedMaterials' => 'Applied Materials',
        'AurisRobotics' => 'Auris Robotics',
        'Bio-Rad' => 'Bio-Rad',
        'BirdTechnologies' => 'Bird Technologies',
        'CDW' => 'CDW',
        'CaTechnologies' => 'CA Technologies',
        'CbsInteractive' => 'CBS Interactive',
        'DunAndBradstreet' => 'Dun & Bradstreet',
        'E&JGalloWinery' => 'E&J Gallo Winery',
        'GoPro' => 'GoPro',
        'GreenDot' => 'Green Dot',
        'GreenMountainPower' => 'Green Mountain Power',
        'HdsGlobal' => 'HDS Global',
        'JuniperNetworks' => 'Juniper Networks',
        'LibertyUniversity' => 'Liberty University',
        'Macys' => "Macy's",
        'MobileIron' => 'MobileIron',
        'NbcUniversal' => 'NBC Universal',
        'NetApp' => 'NetApp',
        'NetFlix' => 'Netflix',
        'NetGear' => 'NETGEAR',
        'NorthropGrumman' => 'Northrop Grumman',
        'OptoVue' => 'OptoVue',
        'PlayStation' => 'PlayStation',
        'Pratt&Whitney' => 'Pratt & Whitney',
        'RingCentral' => 'RingCentral',
        'TheClimateCorporation' => 'The Climate Corporation',
        'ThermoFisherScientific' => 'Thermo Fisher Scientific',
        'VfCorporation' => 'VF Corporation',
        'VolkswagenElectronicsResearchLaboratory' => 'Volkswagen Electronics Research Laboratory',
        'WageWorks' => 'WageWorks',
        'WellsFargo' => 'Wells Fargo',
        'WesternUnion' => 'Western Union',
        'YP' => 'YP',
    ];

    /**
     * Filenames to surface in the homepage "Trusted By" strip, in display order.
     */
    private const FEATURED = [
        'Apple', 'NetFlix', 'Oracle', 'Intuit', 'Chevron', 'Airbus',
        'Seagate', 'NetApp', 'WellsFargo', 'Experian', 'Ericsson', 'GoPro',
        'WesternUnion', 'Roche',
    ];

    /**
     * Optional sample website links (demonstrates the optional-link feature).
     */
    private const WEBSITES = [
        'Apple' => 'https://www.apple.com',
        'NetFlix' => 'https://www.netflix.com',
        'Oracle' => 'https://www.oracle.com',
        'Intuit' => 'https://www.intuit.com',
        'Chevron' => 'https://www.chevron.com',
        'Airbus' => 'https://www.airbus.com',
        'Seagate' => 'https://www.seagate.com',
        'NetApp' => 'https://www.netapp.com',
        'WellsFargo' => 'https://www.wellsfargo.com',
        'Experian' => 'https://www.experian.com',
        'Ericsson' => 'https://www.ericsson.com',
        'GoPro' => 'https://www.gopro.com',
        'WesternUnion' => 'https://www.westernunion.com',
        'Roche' => 'https://www.roche.com',
    ];

    public function run(): void
    {
        $sourceDir = database_path('seeders/assets/client-logos');

        if (! File::isDirectory($sourceDir)) {
            $this->command?->warn("Client logo assets not found at {$sourceDir} — skipping ClientSeeder.");

            return;
        }

        $files = collect(File::files($sourceDir))
            ->filter(fn ($f) => in_array(strtolower($f->getExtension()), ['jpg', 'jpeg', 'png', 'webp', 'gif'], true));

        // Featured logos first (in their curated order), then the rest alphabetically.
        $ordered = $files->sortBy(function ($file) {
            $base = $file->getFilenameWithoutExtension();
            $featuredIndex = array_search($base, self::FEATURED, true);

            return $featuredIndex !== false
                ? sprintf('0_%02d', $featuredIndex)   // featured: keep curated order
                : '1_'.strtolower($base);           // others: alphabetical
        })->values();

        $disk = Storage::disk('public');
        $sortOrder = 0;

        foreach ($ordered as $file) {
            $base = $file->getFilenameWithoutExtension();
            $name = self::NAMES[$base] ?? $this->humanize($base);

            // Copy the logo onto the public disk under a stable path (idempotent overwrite).
            $logoPath = 'clients/seed/'.$file->getFilename();
            $disk->put($logoPath, File::get($file->getPathname()));
            ImageOptimizer::optimize($logoPath);

            Client::updateOrCreate(
                ['name' => $name],
                [
                    'logo' => $logoPath,
                    'website_url' => self::WEBSITES[$base] ?? null,
                    'is_featured' => in_array($base, self::FEATURED, true),
                    'sort_order' => $sortOrder++,
                    'status' => 'active',
                ]
            );
        }

        Client::clearCache();

        $this->command?->info("Seeded {$ordered->count()} clients.");
    }

    /**
     * Turn a CamelCase / token filename into a spaced, readable name.
     */
    private function humanize(string $value): string
    {
        $value = str_replace(['&', '-'], [' & ', '-'], $value);
        // Insert a space between a lowercase/digit and an uppercase letter.
        $value = preg_replace('/(?<=[a-z0-9])(?=[A-Z])/', ' ', $value);

        return trim(preg_replace('/\s+/', ' ', $value));
    }
}
