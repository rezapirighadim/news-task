<?php

namespace App\Adapters;

use App\Contracts\NewsSourceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NYTimesAdapter implements NewsSourceInterface
{
    private string $apiKey;
    private string $baseUrl = 'https://api.nytimes.com/svc';

    public function __construct()
    {
        $this->apiKey = config('services.nytimes.key');
    }

    public function fetchArticles(array $params = []): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/topstories/v2/home.json", [
                'api-key' => $this->apiKey
            ]);

            if (!$response->successful()) {
                Log::error('NYTimes fetch failed', ['status' => $response->status()]);
                return [];
            }

            $results = $response->json()['results'] ?? [];
            return $this->transform($results);
        } catch (\Exception $e) {
            Log::error('NYTimes exception', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function getSourceName(): string
    {
        return 'New York Times';
    }

    private function transform(array $articles): array
    {
        return array_map(function ($article) {
            $multimedia = $article['multimedia'][0] ?? null;
            return [
                'title' => $article['title'] ?? 'Untitled',
                'description' => $article['abstract'] ?? null,
                'content' => $article['abstract'] ?? null,
                'url' => $article['url'] ?? null,
                'url_to_image' => $multimedia['url'] ?? null,
                'published_at' => $article['published_date'] ?? now(),
                'author_name' => $article['byline'] ?? 'New York Times',
                'external_id' => $article['uri'] ?? null,
                'categories' => [$article['section'] ?? 'general']
            ];
        }, $articles);
    }
}
