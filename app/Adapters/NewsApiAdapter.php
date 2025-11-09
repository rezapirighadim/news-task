<?php

namespace App\Adapters;

use App\Contracts\NewsSourceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsApiAdapter implements NewsSourceInterface
{
    private string $apiKey;
    private string $baseUrl = 'https://newsapi.org/v2';

    public function __construct()
    {
        $this->apiKey = config('services.newsapi.key');
    }

    public function fetchArticles(array $params = []): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/top-headlines", [
                'apiKey' => $this->apiKey,
                'pageSize' => $params['limit'] ?? 100,
                'page' => $params['page'] ?? 1,
                'language' => 'en'
            ]);

            if (!$response->successful()) {
                Log::error('NewsAPI fetch failed', ['status' => $response->status()]);
                return [];
            }

            return $this->transform($response->json()['articles'] ?? []);
        } catch (\Exception $e) {
            Log::error('NewsAPI exception', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function getSourceName(): string
    {
        return 'NewsAPI';
    }

    private function transform(array $articles): array
    {
        return array_map(function ($article) {
            return [
                'title' => $article['title'] ?? 'Untitled',
                'description' => $article['description'] ?? null,
                'content' => $article['content'] ?? null,
                'url' => $article['url'] ?? null,
                'url_to_image' => $article['urlToImage'] ?? null,
                'published_at' => $article['publishedAt'] ?? now(),
                'author_name' => $article['author'] ?? 'Unknown',
                'external_id' => null,
                'categories' => ['general']
            ];
        }, $articles);
    }
}
