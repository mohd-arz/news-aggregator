<?php

namespace App\Http\Resources\Api\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      schema="NewsDetailsResource",
 *      title="NewsDetailsResource",
 *      description="News Details Resource",
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
 *          property="url",
 *          type="string",
 *          description="URL of the News",
 *          example="https://example.com"
 *      ),
 *      @OA\Property(
 *          property="author",
 *          type="string",
 *          description="Author of the News",
 *          example="John Doe"
 *      ),
 *      @OA\Property(
 *          property="url_to_image",
 *          type="string",
 *          description="URL to Image of the News",
 *          example="https://example.com/image.jpg"
 *      ),
 *      @OA\Property(
 *          property="published_at",
 *          type="string",
 *          description="Published At of the News",
 *          example="2021-01-01 00:00:00"
 *      )
 * )
 */
class NewsDetailsResource extends JsonResource
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
            'url' => $this->url,
            'author' => $this->author->name ?? 'Unknown',
            'url_to_image' => $this->url_to_image,
            'published_at' => $this->published_at,
        ];
    }
}
