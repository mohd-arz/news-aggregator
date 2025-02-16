<?php

namespace App\Http\Resources\Api\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      title="NewsResource",
 *      description="News Resource",
 *      type="object",
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="ID of the News",
 *          example=1
 *      ),
 *      @OA\Property(
 *          property="source",
 *          type="string",
 *          description="Source of the News",
 *          example="BBC"
 *      ),
 *      @OA\Property(
 *          property="category",
 *          type="string",
 *          description="Category of the News",
 *          example="Technology"
 *      ),
 *      @OA\Property(
 *          property="title",
 *          type="string",
 *          description="Title of the News",
 *          example="The title of the news"
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *          description="Description of the News",
 *          example="The description of the news"
 *      ),
 *      @OA\Property(
 *          property="published_at",
 *          type="string",
 *          description="Published At of the News",
 *          example="2021-01-01 00:00:00"
 *      )
 * )
 */
class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'source' => $this->source->name ?? 'Unknown',
            'category' => $this->category->name ?? 'Unknown',
            'title' => $this->title,
            'description' => $this->description,
            'published_at' => $this->published_at,
        ];
    }
}
