<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\DropDown\AuthorResource;
use App\Http\Resources\Api\DropDown\CategoryResource;
use App\Http\Resources\Api\DropDown\PreferenceResource;
use App\Http\Resources\Api\DropDown\SourceResource;
use App\Models\Author;
use App\Models\Category;
use App\Models\Preference;
use App\Models\Source;
use App\Trait\ResponseTrait;
use Illuminate\Http\Request;

class DropDownController extends Controller
{
    use ResponseTrait;

    /**
     * @OA\Get(
     *     path="/api/v1/dropdown/categories",
     *     summary="Get Categories",
     *     description="Get Categories",
     *     operationId="getCategories",
     *     tags={"DropDown"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Categories",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/CategoryResource")
     *             )
     *         )
     *     )
     * )
     */
    public function getCategories()
    {
        $categories = Category::all();
        return $this->successResponse('Categories', CategoryResource::collection($categories));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/dropdown/sources",
     *     summary="Get Sources",
     *     description="Get Sources",
     *     operationId="getSources",
     *     tags={"DropDown"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sources",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/SourceResource")
     *             )
     *         )
     *     )
     * )
     */
    public function getSources()
    {
        $sources = Source::all();
        return $this->successResponse('Sources', SourceResource::collection($sources));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/dropdown/authors",
     *     summary="Get Authors",
     *     description="Get Authors",
     *     operationId="getAuthors",
     *     tags={"DropDown"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Authors",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/AuthorResource")
     *             )
     *         )
     *     )
     * )
     */
    public function getAuthors()
    {
        $authors = Author::all();
        return $this->successResponse('Authors', AuthorResource::collection($authors));
    }
}
