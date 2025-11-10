<?php

use App\Models\Article;
use App\Repositories\ArticleRepository;
use App\Services\ArticleService;

beforeEach(function () {
    $this->repository = Mockery::mock(ArticleRepository::class);
    $this->service = new ArticleService($this->repository);
});

test('search delegates to repository', function () {
    $filters = ['search' => 'test'];
    $expected = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);

    $this->repository->shouldReceive('search')
        ->once()
        ->with($filters)
        ->andReturn($expected);

    $result = $this->service->search($filters);

    expect($result)->toBe($expected);
});

test('get by id delegates to repository', function () {
    $article = new Article();

    $this->repository->shouldReceive('getById')
        ->once()
        ->with(1)
        ->andReturn($article);

    $result = $this->service->getById(1);

    expect($result)->toBe($article);
});
