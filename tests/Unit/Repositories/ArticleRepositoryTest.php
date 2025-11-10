<?php

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Repositories\ArticleRepository;

beforeEach(function () {
    $this->repository = new ArticleRepository();
    $this->source = Source::factory()->create();
});

test('search returns paginated results', function () {
    Article::factory()->count(20)->create(['source_id' => $this->source->id]);

    $results = $this->repository->search([]);

    expect($results)->toBeInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class)
        ->and($results->total())->toBe(20);
});

test('search filters by keyword', function () {
    Article::factory()->create([
        'title' => 'Laravel Framework',
        'source_id' => $this->source->id
    ]);
    Article::factory()->create([
        'title' => 'React Tutorial',
        'source_id' => $this->source->id
    ]);

    $results = $this->repository->search(['search' => 'Laravel']);

    expect($results->total())->toBe(1)
        ->and($results->first()->title)->toContain('Laravel');
});

test('search filters by source', function () {
    $source1 = Source::factory()->create(['slug' => 'source-1']);
    $source2 = Source::factory()->create(['slug' => 'source-2']);

    Article::factory()->count(3)->create(['source_id' => $source1->id]);
    Article::factory()->count(2)->create(['source_id' => $source2->id]);

    $results = $this->repository->search(['source' => 'source-1']);

    expect($results->total())->toBe(3);
});

test('search filters by category', function () {
    $category = Category::factory()->create(['slug' => 'tech']);
    $article = Article::factory()->create(['source_id' => $this->source->id]);
    $article->categories()->attach($category);

    Article::factory()->count(2)->create(['source_id' => $this->source->id]);

    $results = $this->repository->search(['category' => 'tech']);

    expect($results->total())->toBe(1);
});

test('search filters by date range', function () {
    Article::factory()->create([
        'published_at' => '2024-01-01',
        'source_id' => $this->source->id
    ]);
    Article::factory()->create([
        'published_at' => '2024-06-01',
        'source_id' => $this->source->id
    ]);

    $results = $this->repository->search([
        'from' => '2024-05-01',
        'to' => '2024-12-31'
    ]);

    expect($results->total())->toBe(1);
});

test('search filters by author', function () {
    $author = Author::factory()->create(['name' => 'John Doe']);
    Article::factory()->create([
        'author_id' => $author->id,
        'source_id' => $this->source->id
    ]);
    Article::factory()->count(2)->create(['source_id' => $this->source->id]);

    $results = $this->repository->search(['author' => 'John']);

    expect($results->total())->toBe(1);
});

test('create or update creates new article', function () {
    $data = [
        'title' => 'New Article',
        'url' => 'https://example.com/new',
        'description' => 'Description',
        'content' => 'Content',
        'url_to_image' => 'https://example.com/image.jpg',
        'published_at' => now(),
        'author_name' => 'Jane Doe',
        'categories' => ['Technology']
    ];

    $article = $this->repository->createOrUpdate($data, $this->source->id);

    expect($article)->toBeInstanceOf(Article::class)
        ->and($article->title)->toBe('New Article')
        ->and($article->author->name)->toBe('Jane Doe')
        ->and($article->categories)->toHaveCount(1);
});

test('create or update updates existing article', function () {
    $existing = Article::factory()->create([
        'url' => 'https://example.com/article',
        'title' => 'Old Title',
        'source_id' => $this->source->id
    ]);

    $data = [
        'title' => 'Updated Title',
        'url' => 'https://example.com/article',
        'description' => 'New Description',
        'content' => 'New Content',
        'url_to_image' => null,
        'published_at' => now(),
        'author_name' => 'Author',
        'categories' => []
    ];

    $article = $this->repository->createOrUpdate($data, $this->source->id);

    expect($article->id)->toBe($existing->id)
        ->and($article->title)->toBe('Updated Title');
});

test('get by id returns article with relationships', function () {
    $article = Article::factory()->create(['source_id' => $this->source->id]);
    $category = Category::factory()->create();
    $article->categories()->attach($category);

    $result = $this->repository->getById($article->id);

    expect($result->relationLoaded('source'))->toBeTrue()
        ->and($result->relationLoaded('categories'))->toBeTrue();
});
