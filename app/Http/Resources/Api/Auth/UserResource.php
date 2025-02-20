<?php

namespace App\Http\Resources\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *      title="UserResource",
 *      description="User Resource",
 *      type="object",
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="ID of the User",
 *          example=1
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          description="Name of the User",
 *          example="John Doe"
 *      ),
 *      @OA\Property(
 *          property="email",
 *          type="string",
 *          description="Email of the User",
 *          example="john.doe@example.com"
 *      )
 * )
 */
class UserResource extends JsonResource
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
            'email' => $this->email,
        ];
    }
}
