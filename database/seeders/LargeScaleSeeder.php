<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LargeScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chunkSize = 1000;
        $totalRecords = 100000;
        $locales = ['en', 'fr', 'es'];

        $translations = [];
        for ($i = 0; $i < $totalRecords; $i++) {
            $translations[] = [
                'locale' => $locales[array_rand($locales)],
                'key' => 'key_' . $i . '_' . Str::random(5),
                'content' => 'Content for key ' . $i . ' ' . Str::random(20),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($translations) >= $chunkSize) {
                DB::table('translations')->insert($translations);
                $translations = [];
                echo "Inserted " . ($i + 1) . " records...\n";
            }
        }

        if (count($translations) > 0) {
            DB::table('translations')->insert($translations);
        }
    }
}
