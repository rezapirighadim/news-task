<?php

namespace App\Http\OpenApi\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UserPreferences",
 *     type="object",
 *     @OA\Property(property="preferred_sources", type="array", @OA\Items(type="integer")),
 *     @OA\Property(property="preferred_categories", type="array", @OA\Items(type="integer")),
 *     @OA\Property(property="preferred_authors", type="array", @OA\Items(type="integer")),
 * )
 */
class PreferenceSchema {}
