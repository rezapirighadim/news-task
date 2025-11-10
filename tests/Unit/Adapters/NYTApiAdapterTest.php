<?php

namespace Tests\Unit\Adapters;

use App\Adapters\NYTimesAdapter;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    config(['services.nytimes.key' => 'test-key']);
});

test('fetches articles successfully', function () {
    Http::fake([
        'api.nytimes.com/*' => Http::response([
            'results' => [
                [
                    'title' => 'Test Title',
                    'abstract' => 'Test Abstract',
                    'url' => 'https://nytimes.com/test',
                    'uri' => 'nyt://article/123',
                    'byline' => 'By Test Author',
                    'published_date' => '2024-01-01T10:00:00Z',
                    'section' => 'us',
                    'multimedia' => [
                        [
                            'url' => 'https://nytimes.com/image.jpg',
                        ]
                    ],
                ]
            ]
        ], 200)
    ]);

    $adapter = new NYTimesAdapter();
    $articles = $adapter->fetchArticles();

    expect($articles)->toHaveCount(1)
        ->and($articles[0]['title'])->toBe('Test Title')
        ->and($articles[0]['description'])->toBe('Test Abstract')
        ->and($articles[0]['content'])->toBe('Test Abstract')
        ->and($articles[0]['url'])->toBe('https://nytimes.com/test')
        ->and($articles[0]['url_to_image'])->toBe('https://nytimes.com/image.jpg')
        ->and($articles[0]['published_at'])->toBe('2024-01-01T10:00:00Z')
        ->and($articles[0]['author_name'])->toBe('By Test Author')
        ->and($articles[0]['external_id'])->toBe('nyt://article/123')
        ->and($articles[0]['categories'])->toBe(['us']);
});

test('handles api failure gracefully', function () {
    Http::fake([
        'api.nytimes.com/*' => Http::response([], 500)
    ]);

    $adapter = new NYTimesAdapter();
    $articles = $adapter->fetchArticles();

    expect($articles)->toBeEmpty();
});

test('returns correct source name', function () {
    $adapter = new NYTimesAdapter();
    expect($adapter->getSourceName())->toBe('New York Times');
});
