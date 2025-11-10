<?php

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use App\Models\User;

describe('Article endpoints', function () {
    beforeEach(function () {
        $this->source = Source::factory()->create();
        Article::factory()->count(15)->create(['source_id' => $this->source->id]);
    });

    test('can list articles', function () {
        $response = $this->getJson('/api/v1/articles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'url', 'source', 'published_at']
                ],
                'links',
                'meta'
            ]);
    });

    test('can search articles by keyword', function () {
        Article::factory()->create([
            'title' => 'Unique Laravel Test',
            'source_id' => $this->source->id
        ]);

        $response = $this->getJson('/api/v1/articles?search=Unique Laravel');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Unique Laravel Test']);
    });

    test('can filter by source', function () {
        $response = $this->getJson("/api/v1/articles?source={$this->source->slug}");

        $response->assertStatus(200);

        $articles = $response->json('data');
        expect($articles)->each(
            fn ($article) => $article->source->slug->toBe($this->source->slug)
        );
    });

    test('can filter by category', function () {
        $category = Category::factory()->create(['slug' => 'tech']);
        $article = Article::factory()->create(['source_id' => $this->source->id]);
        $article->categories()->attach($category);

        $response = $this->getJson('/api/v1/articles?category=tech');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });

    test('can filter by date range', function () {
        $response = $this->getJson('/api/v1/articles?from=2024-01-01&to=2024-12-31');

        $response->assertStatus(200);
    });

    test('validates date filters', function () {
        $response = $this->getJson('/api/v1/articles?from=invalid-date');

        $response->assertStatus(422)
            ->assertJsonValidationErrors('from');
    });

    test('can get single article', function () {
        $article = Article::first();

        $response = $this->getJson("/api/v1/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $article->id,
                    'title' => $article->title,
                ]
            ]);
    });

    test('returns 404 for non-existent article', function () {
        $response = $this->getJson('/api/v1/articles/99999');

        $response->assertStatus(404);
    });

    test('pagination works correctly', function () {
        $response = $this->getJson('/api/v1/articles?per_page=5');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure(['meta' => ['current_page', 'last_page']]);
    });
});

describe('Metadata endpoints', function () {
    test('can list sources', function () {
        Source::factory()->count(3)->create(['is_active' => true]);

        $response = $this->getJson('/api/v1/sources');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug']
                ]
            ])
            ->assertJsonCount(3, 'data');
    });

    test('only lists active sources', function () {
        Source::factory()->create(['is_active' => true]);
        Source::factory()->create(['is_active' => false]);

        $response = $this->getJson('/api/v1/sources');

        $response->assertJsonCount(1, 'data');
    });

    test('can list categories', function () {
        Category::factory()->count(5)->create();

        $response = $this->getJson('/api/v1/categories');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    });

    test('can list authors', function () {
        $author = Author::factory()->create();
        Article::factory()->create([
            'author_id' => $author->id,
            'source_id' => Source::factory()
        ]);


        $response = $this->getJson('/api/v1/authors');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    });
});
