<?php

namespace App\Http\Resources\Api\Preference;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="UserCategoryResource",
 *     title="CategoryResource",
 *     description="Category Resource",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the Category",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the Category",
 *         example="Technology"
 *     )
 * )
 */
class CategoryResource extends JsonResource
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
            'name' => $this->name,
        ];
    }
}
