<?php

namespace App\Http\Resources\Api\Preference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      title="GetPreferenceResource",
 *      description="Preference Resource",
 *      type="object",
 *      @OA\Property(
 *          property="categories",
 *          type="array",
 *          description="Categories of the User",
 *          @OA\Items(
 *              ref="#/components/schemas/UserCategoryResource"
 *          )
 *      ),
 *      @OA\Property(
 *          property="sources",
 *          type="array",
 *          description="Sources of the User",
 *          @OA\Items(
 *              ref="#/components/schemas/UserSourceResource"
 *          )
 *      ),
 *      @OA\Property(
 *          property="authors",
 *          type="array",
 *          description="Authors of the User",
 *          @OA\Items(
 *              ref="#/components/schemas/UserAuthorResource"
 *          )
 *      )
 * )
 */
class GetPreferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'categories' => CategoryResource::collection($this->categoryPreferences),
            'sources' => SourceResource::collection($this->sourcePreferences),
            'authors' => AuthorResource::collection($this->authorPreferences)
        ];
    }
}
