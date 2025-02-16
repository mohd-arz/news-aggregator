<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\News\GetAllNewsResource;
use App\Models\News;
use App\Services\Api\NewsStrategy\NewsApiService;
use App\Trait\PaginationTrait;
use App\Trait\ResponseTrait;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    use ResponseTrait,PaginationTrait;

    /**
     *  @OA\Get(
     *     path="/news",
     *     operationId="getAllNews",
     *     tags={"News"},
     *     summary="Get All News",
     *     description="Get all news from the database",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Page number"
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer"),
     *         description="Number of items per page"
     *     ),
     *    @OA\Response(
     *        response=200,
     *        description="News Fetched Successfully",
     *        @OA\JsonContent(
     *           @OA\Property(property="data", type="array",
     *                  @OA\Items( ref="#/components/schemas/GetAllNewsResource"),
     *              ),
*                  @OA\Property(property="pagination", type="object", ref="#/components/schemas/Pagination")
     *         )
     *    ),
     *   @OA\Response(
     *       response=422,
     *      description="Something went wrong",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *  )
     * )
     */
    public function getAllNews(Request $request){
        try{
            $no_of_items = $request->query('per_page',10);
            $news = News::with(['source','category'])
            ->orderBy('published_at','desc')
            ->paginate($no_of_items);

            return $this->successResponse('News Fetched Successfully',GetAllNewsResource::collection($news),200,
                $this->pagination($news)
            );
        }catch(\Exception $e){
            return $this->errorResponse('Something went wrong',$e->getMessage(),422);
        }
    }
}
