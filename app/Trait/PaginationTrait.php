<?php

namespace App\Trait;


/** 
*   @OA\Schema(
*   schema="Pagination",
*   title="Pagination",
*   description="Pagination response",
*   type="object",
*   @OA\Property(property="total", type="integer", example=100),
*   @OA\Property(property="per_page", type="integer", example=10),
*   @OA\Property(property="current_page", type="integer", example=1),
*   @OA\Property(property="last_page", type="integer", example=10),
*   @OA\Property(property="next_page_url", type="string", example="http://example.com?page=2"),
*   @OA\Property(property="prev_page_url", type="string", example=null),
* )
*/
trait PaginationTrait
{
    /**
     * @param mixed $paginator
     * @return array<string, mixed>
     */
    public function pagination($paginator): array
    {
        return [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'next_page_url' => $paginator->nextPageUrl(),
            'prev_page_url' => $paginator->previousPageUrl(),
        ];
    }
}