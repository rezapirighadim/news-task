<?php
//
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\MetadataController;
use App\Http\Controllers\Api\PreferenceController;

// Public routes with rate limiting
Route::prefix('v1')->middleware('throttle:api')->group(function () {
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);

    // Metadata
    Route::get('/sources', [MetadataController::class, 'sources']);
    Route::get('/categories', [MetadataController::class, 'categories']);
    Route::get('/authors', [MetadataController::class, 'authors']);

});

// Protected routes (stricter limits)
Route::prefix('v1')
    ->middleware(['auth:sanctum', 'throttle:60,1'])
    ->group(function () {
        Route::get('/preferences', [PreferenceController::class, 'show']);
        Route::post('/preferences', [PreferenceController::class, 'update']);
        Route::get('/feed', [PreferenceController::class, 'personalizedFeed']);
    });
