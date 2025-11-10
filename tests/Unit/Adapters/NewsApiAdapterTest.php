<?php

use App\Adapters\NewsApiAdapter;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config(['services.newsapi.key' => 'test-key']);
});

test('fetches articles successfully', function () {
    Http::fake([
        'newsapi.org/*' => Http::response([
            'articles' => [
                [
                    'title' => 'Test Article',
                    'description' => 'Test Description',
                    'content' => 'Test Content',
                    'url' => 'https://example.com/article',
                    'urlToImage' => 'https://example.com/image.jpg',
                    'publishedAt' => '2024-01-01T10:00:00Z',
                    'author' => 'John Doe',
                ]
            ]
        ], 200)
    ]);

    $adapter = new NewsApiAdapter();
    $articles = $adapter->fetchArticles();

    expect($articles)->toHaveCount(1)
        ->and($articles[0]['title'])->toBe('Test Article')
        ->and($articles[0]['author_name'])->toBe('John Doe');
});

test('handles api failure gracefully', function () {
    Http::fake([
        'newsapi.org/*' => Http::response([], 500)
    ]);

    $adapter = new NewsApiAdapter();
    $articles = $adapter->fetchArticles();

    expect($articles)->toBeEmpty();
});

test('returns correct source name', function () {
    $adapter = new NewsApiAdapter();
    expect($adapter->getSourceName())->toBe('NewsAPI');
});
