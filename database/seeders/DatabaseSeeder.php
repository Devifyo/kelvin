<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);
        $this->call(ConsultingSeeder::class);
        $this->call(TrainingSeeder::class);
        $this->call(PaperSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(WelcomePageContentSeeder::class);
        $this->call(AboutPageContentSeeder::class);
        $this->call(PrivacyPageContentSeeder::class);
        $this->call(TermsPageContentSeeder::class);
    }
}
