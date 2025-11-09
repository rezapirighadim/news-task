<?php

namespace Database\Seeders;

use App\Models\Source;
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
        // Create sources
        $sources = [
            ['name' => 'NewsAPI', 'slug' => 'newsapi'],
            ['name' => 'The Guardian', 'slug' => 'the-guardian'],
            ['name' => 'New York Times', 'slug' => 'new-york-times'],
        ];

        foreach ($sources as $source) {
            Source::firstOrCreate($source);
        }
    }
}
