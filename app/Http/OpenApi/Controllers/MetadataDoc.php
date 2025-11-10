<?php

namespace App\Http\OpenApi\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Metadata",
 *     description="Public data sources for dropdowns / filters"
 * )
 */
class MetadataDoc
{
    /**
     * @OA\Get(
     *     path="/api/v1/sources",
     *     tags={"Metadata"},
     *     summary="List active sources",
     *     @OA\Response(
     *         response=200,
     *         description="List of sources",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(
     *                property="data",
     *                type="array",
     *                @OA\Items(
     *                   @OA\Property(property="id", type="integer"),
     *                   @OA\Property(property="name", type="string"),
     *                   @OA\Property(property="slug", type="string")
     *                )
     *            )
     *         )
     *     )
     * )
     */
    public function sources() {}

    /**
     * @OA\Get(
     *     path="/api/v1/categories",
     *     tags={"Metadata"},
     *     summary="List categories",
     *     @OA\Response(
     *         response=200,
     *         description="List of categories",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(
     *                property="data",
     *                type="array",
     *                @OA\Items(
     *                   @OA\Property(property="id", type="integer"),
     *                   @OA\Property(property="name", type="string"),
     *                   @OA\Property(property="slug", type="string")
     *                )
     *            )
     *         )
     *     )
     * )
     */
    public function categories() {}

    /**
     * @OA\Get(
     *     path="/api/v1/authors",
     *     tags={"Metadata"},
     *     summary="List authors with article count",
     *     @OA\Response(
     *         response=200,
     *         description="List of authors",
     *         @OA\JsonContent(
     *            type="object",
     *            @OA\Property(
     *                property="data",
     *                type="array",
     *                @OA\Items(
     *                   @OA\Property(property="id", type="integer"),
     *                   @OA\Property(property="name", type="string"),
     *                   @OA\Property(property="articles_count", type="integer")
     *                )
     *            )
     *         )
     *     )
     * )
     */
    public function authors() {}
}
