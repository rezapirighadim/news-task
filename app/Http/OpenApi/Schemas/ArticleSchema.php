<?php

namespace App\Http\OpenApi\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="title", type="string"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="content", type="string", nullable=true),
 *     @OA\Property(property="url", type="string"),
 *     @OA\Property(property="url_to_image", type="string", nullable=true),
 *     @OA\Property(property="published_at", type="string", format="date-time"),
 *     @OA\Property(
 *          property="source",
 *          type="object",
 *          @OA\Property(property="id", type="integer"),
 *          @OA\Property(property="name", type="string"),
 *          @OA\Property(property="slug", type="string"),
 *     ),
 *     @OA\Property(
 *         property="author",
 *         type="object",
 *         nullable=true,
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="name", type="string")
 *     ),
 *     @OA\Property(
 *         property="categories",
 *         type="array",
 *         @OA\Items(
 *            @OA\Property(property="id", type="integer"),
 *            @OA\Property(property="name", type="string"),
 *            @OA\Property(property="slug", type="string")
 *         )
 *     )
 * )
 */
class ArticleSchema {}
