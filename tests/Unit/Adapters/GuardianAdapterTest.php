<?php

use App\Adapters\GuardianAdapter;

beforeEach(function () {
    config(['services.guardian.key' => 'test-key']);
});

test('guardian fetches articles successfully', function () {
    Http::fake([
        'content.guardianapis.com/*' => Http::response([
            'response' => [
                'results' => [
                    [
                        'webTitle' => 'Guardian Article',
                        'webUrl' => 'https://guardian.com/article',
                        'webPublicationDate' => '2024-01-01T10:00:00Z',
                        'sectionName' => 'Technology',
                        'id' => 'tech-123',
                        'fields' => [
                            'trailText' => 'Description',
                            'bodyText' => 'Content',
                            'byline' => 'Jane Smith',
                            'thumbnail' => 'https://guardian.com/image.jpg'
                        ]
                    ]
                ]
            ]
        ], 200)
    ]);

    $adapter = new GuardianAdapter();
    $articles = $adapter->fetchArticles();

    expect($articles)->toHaveCount(1)
        ->and($articles[0]['title'])->toBe('Guardian Article')
        ->and($articles[0]['categories'])->toContain('Technology');
});

test('guardian returns correct source name', function () {
    $adapter = new GuardianAdapter();
    expect($adapter->getSourceName())->toBe('The Guardian');
});
