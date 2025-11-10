<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Source;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Http\JsonResponse;

class MetadataController extends Controller
{
    public function sources(): JsonResponse
    {
        $sources = Source::where('is_active', true)
            ->select('id', 'name', 'slug')
            ->get();

        return response()->json(['data' => $sources]);
    }

    public function categories(): JsonResponse
    {
        $categories = Category::select('id', 'name', 'slug')
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $categories]);
    }

    public function authors(): JsonResponse
    {
        $authors = Author::select('id', 'name')
            ->withCount('articles')
            ->groupBy('authors.id', 'authors.name')
            ->having('articles_count', '>', 0)
            ->orderBy('name')
            ->get();

        return response()->json(['data' => $authors]);
    }
}
