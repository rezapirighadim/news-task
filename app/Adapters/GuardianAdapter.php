<?php

namespace App\Adapters;

use App\Contracts\NewsSourceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GuardianAdapter implements NewsSourceInterface
{
    private string $apiKey;
    private string $baseUrl = 'https://content.guardianapis.com';

    public function __construct()
    {
        $this->apiKey = config('services.guardian.key');
    }

    public function fetchArticles(array $params = []): array
    {
        try {
            $response = Http::get("{$this->baseUrl}/search", [
                'api-key' => $this->apiKey,
                'page-size' => $params['limit'] ?? 100,
                'show-fields' => 'all'
            ]);

            if (!$response->successful()) {
                Log::error('Guardian fetch failed', ['status' => $response->status()]);
                return [];
            }

            $results = $response->json()['response']['results'] ?? [];
            return $this->transform($results);
        } catch (\Exception $e) {
            Log::error('Guardian exception', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function getSourceName(): string
    {
        return 'The Guardian';
    }

    private function transform(array $articles): array
    {
        return array_map(function ($article) {
            $fields = $article['fields'] ?? [];
            return [
                'title' => $article['webTitle'] ?? 'Untitled',
                'description' => $fields['trailText'] ?? null,
                'content' => $fields['bodyText'] ?? null,
                'url' => $article['webUrl'] ?? null,
                'url_to_image' => $fields['thumbnail'] ?? null,
                'published_at' => $article['webPublicationDate'] ?? now(),
                'author_name' => $fields['byline'] ?? 'The Guardian',
                'external_id' => $article['id'] ?? null,
                'categories' => [$article['sectionName'] ?? 'general']
            ];
        }, $articles);
    }
}
