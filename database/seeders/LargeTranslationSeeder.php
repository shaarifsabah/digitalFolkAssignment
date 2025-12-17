<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LargeTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Locales
        $locales = ['en', 'fr', 'es', 'de', 'it'];
        $localeIds = [];
        foreach ($locales as $code) {
            $l = \App\Models\Locale::firstOrCreate(['code' => $code], ['name' => ucfirst($code)]);
            $localeIds[$code] = $l->id;
        }

        // 2. Prepare Data
        $chunks = [];
        $batchSize = 2000;
        $totalRecords = 100000;
        $keysPerLocale = $totalRecords / count($locales);

        $now = now();

        $this->command->info("Generating {$totalRecords} records...");

        foreach ($locales as $code) {
            $lid = $localeIds[$code];
            for ($i = 1; $i <= $keysPerLocale; $i++) {
                $chunks[] = [
                    'locale_id' => $lid,
                    'key' => "key_{$i}",
                    'content' => "Translation content for key {$i} in language {$code}. " . \Illuminate\Support\Str::random(20),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                if (count($chunks) >= $batchSize) {
                    \App\Models\Translation::insert($chunks);
                    $chunks = [];
                }
            }
        }
        
        if (!empty($chunks)) {
            \App\Models\Translation::insert($chunks);
        }

        $this->command->info("Seeding completed.");
    }
}
