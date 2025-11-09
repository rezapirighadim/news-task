<?php
//
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\MetadataController;
use App\Http\Controllers\Api\PreferenceController;

Route::prefix('v1')->group(function () {

    // Articles
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);

    // Metadata
    Route::get('/sources', [MetadataController::class, 'sources']);
    Route::get('/categories', [MetadataController::class, 'categories']);
    Route::get('/authors', [MetadataController::class, 'authors']);

});

// Protected routes (require authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // User preferences
    Route::get('/preferences', [PreferenceController::class, 'show']);
    Route::post('/preferences', [PreferenceController::class, 'update']);
    Route::get('/feed', [PreferenceController::class, 'personalizedFeed']);

});
