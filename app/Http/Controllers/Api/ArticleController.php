<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleSearchRequest;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleService $service
    ) {}

    public function index(ArticleSearchRequest $request)
    {
        $articles = $this->service->search($request->validated());

        return ArticleResource::collection($articles);
    }

    public function show(int $id)
    {
        $article = $this->service->getById($id);

        if (!$article) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        return new ArticleResource($article);
    }
}
