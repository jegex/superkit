<?php

namespace Database\Seeders;

use App\Models\Locale;
use Illuminate\Database\Seeder;

class LocaleSeeder extends Seeder
{
    public function run(): void
    {
        $locales = json_decode(file_get_contents(database_path('locales.json')), true);

        foreach ($locales as $locale) {
            Locale::updateOrCreate(
                ['code' => $locale['code']],
                [
                    'name' => $locale['name'],
                    'script' => $locale['script'],
                    'native' => $locale['native'],
                    'regional' => $locale['regional'] ?: null,
                ]
            );
        }
    }
}
