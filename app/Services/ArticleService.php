<?php

namespace App\Services;
use App\Models\Article;
use App\Repositories\ArticleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ArticleService
{
    public function __construct(
        private readonly ArticleRepository $repository
    ) {}

    public function search(array $filters): LengthAwarePaginator
    {
        return $this->repository->search($filters);
    }

    public function getById(int $id): ?Article
    {
        return $this->repository->getById($id);
    }
}
