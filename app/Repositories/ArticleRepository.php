<?php

namespace App\Repositories;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\Source;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ArticleRepository
{
    public function search(array $filters): LengthAwarePaginator
    {
        $query = Article::query()
            ->with(['source', 'author', 'categories'])
            ->orderBy('published_at', 'desc');

        // Search keyword
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereFullText(['title', 'description', 'content'], $search)
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        // Filter by source
        if (!empty($filters['source'])) {
            $query->whereHas('source', function ($q) use ($filters) {
                $q->where('slug', $filters['source']);
            });
        }

        // Filter by category
        if (!empty($filters['category'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('slug', $filters['category']);
            });
        }

        // Filter by author
        if (!empty($filters['author'])) {
            $query->whereHas('author', function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['author']}%");
            });
        }

        // Date range
        if (!empty($filters['from'])) {
            $query->where('published_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->where('published_at', '<=', $filters['to']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function createOrUpdate(array $data, int $sourceId): ?Article
    {
        if (empty($data['url'])) {
            return null;
        }

        // Find or create author
        $authorId = null;
        if (!empty($data['author_name'])) {
            $author = Author::firstOrCreate(['name' => $data['author_name']]);
            $authorId = $author->id;
        }

        // Create or update article
        $article = Article::updateOrCreate(
            ['url' => $data['url']],
            [
                'source_id' => $sourceId,
                'author_id' => $authorId,
                'title' => $data['title'],
                'description' => $data['description'],
                'content' => $data['content'],
                'url_to_image' => $data['url_to_image'],
                'published_at' => $data['published_at'],
                'external_id' => $data['external_id'] ?? null,
            ]
        );

        // Attach categories
        if (!empty($data['categories'])) {
            $categoryIds = [];
            foreach ($data['categories'] as $categoryName) {
                $category = Category::firstOrCreate(
                    ['slug' => Str::slug($categoryName)],
                    ['name' => $categoryName]
                );
                $categoryIds[] = $category->id;
            }
            $article->categories()->sync($categoryIds);
        }

        return $article;
    }

    public function getById(int $id): ?Article
    {
        return Article::with(['source', 'author', 'categories'])->find($id);
    }
}
