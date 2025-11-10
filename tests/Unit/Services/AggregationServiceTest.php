<?php


use App\Contracts\NewsSourceInterface;
use App\Models\Source;
use App\Repositories\ArticleRepository;
use App\Services\AggregationService;
use App\Models\Article;

beforeEach(function () {
    $this->repository = Mockery::mock(ArticleRepository::class);
    $this->service = new AggregationService($this->repository);
});

test('fetches from source and stores articles', function () {
    $source = Source::factory()->create(['name' => 'Test Source']);

    $adapter = Mockery::mock(NewsSourceInterface::class);
    $adapter->shouldReceive('getSourceName')->andReturn('Test Source');
    $adapter->shouldReceive('fetchArticles')->andReturn([
        [
            'title' => 'Article 1',
            'url' => 'https://example.com/1',
            'description' => 'Desc',
            'content' => 'Content',
            'url_to_image' => null,
            'published_at' => now(),
            'author_name' => 'Author',
            'categories' => []
        ]
    ]);

    $this->repository->shouldReceive('createOrUpdate')
        ->once()
        ->andReturn(new Article());

    $count = $this->service->fetchFromSource($adapter);

    expect($count)->toBe(1);
});

test('skips inactive sources', function () {
    Source::factory()->create(['name' => 'Test Source', 'is_active' => false]);

    $adapter = Mockery::mock(NewsSourceInterface::class);
    $adapter->shouldReceive('getSourceName')->andReturn('Test Source');

    $count = $this->service->fetchFromSource($adapter);

    expect($count)->toBe(0);
});
