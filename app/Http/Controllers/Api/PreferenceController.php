<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreferenceRequest;
use App\Http\Resources\ArticleResource;
use App\Services\UserPreferenceService;
use Illuminate\Http\Request;

class PreferenceController extends Controller
{
    public function __construct(
        private readonly UserPreferenceService $service
    ) {}

    public function show(Request $request)
    {
        $preferences = $this->service->getPreferences($request->user()->id);

        return response()->json(['data' => $preferences]);
    }

    public function update(PreferenceRequest $request)
    {
        $preferences = $this->service->updatePreferences(
            $request->user()->id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Preferences updated successfully',
            'data' => $preferences
        ]);
    }

    public function personalizedFeed(Request $request)
    {
        $articles = $this->service->getPersonalizedArticles(
            $request->user()->id,
            $request->all()
        );

        return ArticleResource::collection($articles);
    }
}
