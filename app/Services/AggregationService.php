<?php

namespace App\Services;

use App\Contracts\NewsSourceInterface;
use App\Models\Source;
use App\Repositories\ArticleRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

readonly class AggregationService
{
    public function __construct(
        private ArticleRepository $repository
    ) {}

    public function fetchFromSource(NewsSourceInterface $adapter): int
    {
        $sourceName = $adapter->getSourceName();

        Log::info("Starting fetch from {$sourceName}");

        // Get or create source
        $source = Source::firstOrCreate(
            ['name' => $sourceName],
            ['slug' => Str::slug($sourceName)]
        );

        if (!$source->is_active) {
            Log::info("Source {$sourceName} is inactive, skipping");
            return 0;
        }

        // Fetch articles
        $articles = $adapter->fetchArticles();

        if (empty($articles)) {
            Log::warning("No articles fetched from {$sourceName}");
            return 0;
        }

        $count = 0;
        foreach ($articles as $articleData) {
            try {
                $article = $this->repository->createOrUpdate($articleData, $source->id);
                if ($article) {
                    $count++;
                }
            } catch (\Exception $e) {
                Log::error("Failed to save article from {$sourceName}", [
                    'error' => $e->getMessage(),
                    'article' => $articleData['title'] ?? 'unknown'
                ]);
            }
        }

        Log::info("Fetched {$count} articles from {$sourceName}");

        return $count;
    }
}
