<?php

namespace App\Http\OpenApi\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Preferences",
 *     description="User preferences (requires auth)"
 * )
 */
class PreferencesDoc
{
    /**
     * @OA\Get(
     *     path="/api/v1/preferences",
     *     tags={"Preferences"},
     *     summary="Get user preferences",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User preferences",
     *         @OA\JsonContent(ref="#/components/schemas/UserPreferences")
     *     )
     * )
     */
    public function show() {}

    /**
     * @OA\Post(
     *     path="/api/v1/preferences",
     *     tags={"Preferences"},
     *     summary="Update user preferences",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserPreferences")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated preferences",
     *         @OA\JsonContent(ref="#/components/schemas/UserPreferences")
     *     )
     * )
     */
    public function update() {}

    /**
     * @OA\Get(
     *     path="/api/v1/feed",
     *     tags={"Preferences"},
     *     summary="Get personalized feed",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Personalized articles",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Article")
     *         )
     *     )
     * )
     */
    public function personalizedFeed() {}
}
