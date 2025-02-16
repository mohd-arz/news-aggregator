<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\News\GetAllNewsResource;
use App\Http\Resources\Api\News\NewsDetailsResource;
use App\Http\Resources\Api\News\NewsResource;
use App\Models\News;
use App\Services\Api\NewsStrategy\NewsApiService;
use App\Trait\PaginationTrait;
use App\Trait\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

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
     *                  @OA\Items( ref="#/components/schemas/NewsResource"),
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

            return $this->successResponse('News Fetched Successfully',NewsResource::collection($news),200,
                $this->pagination($news)
            );
        }catch(\Exception $e){
            return $this->errorResponse('Something went wrong',$e->getMessage(),422);
        }
    }


    /**
     *  @OA\Get(
     *     path="/for-you",
     *     operationId="getForYou",
     *     tags={"News"},
     *     summary="Get News For You",
     *     description="Get prefferenced news from the database",
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
     *                  @OA\Items( ref="#/components/schemas/NewsResource"),
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

    public function ForYou(Request $request){
        try{
            $no_of_items = $request->query('per_page',10);
            /** @var Guard $auth */
            $auth = auth();
            $user = $auth->user();
            $categories = $user->categoryPreferences->pluck('id')->toArray();
            $sources = $user->sourcePreferences->pluck('id')->toArray();
            $authors = $user->authorPreferences->pluck('id')->toArray();
            
            $news = News::with(['source','category','author'])->where(function($query) use($categories,$sources,$authors){
                return $query->whereIn('category_id',$categories)
                ->orWhereIn('source_id',$sources)
                ->orWhereIn('author_id',$authors);
            })->orderBy('published_at','desc')
            ->paginate($no_of_items);

            return $this->successResponse('News Fetched Successfully',NewsResource::collection($news),200,
                $this->pagination($news)
            );
        }catch(\Exception $e){
            return $this->errorResponse('Something went wrong',$e->getMessage(),422);
        }
    }

    /**
     *  @OA\Get(
     *     path="/news/{news}",
     *     operationId="getNews",
     *     tags={"News"},
     *     summary="Get News",
     *     description="Get a single news from the database",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="news",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the news"
     *     ),
     *    @OA\Response(
     *        response=200,
     *        description="News Fetched Successfully",
     *        @OA\JsonContent(
     *           @OA\Property(property="data", type="object",
     *                  ref="#/components/schemas/NewsDetailsResource"
     *              )
     *         )
     *    ),
     *   @OA\Response(
     *       response=404,
     *      description="Resource not found",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *  )
     * )
     */
    public function getNews(News $news){
        return $this->successResponse('News Fetched Successfully',NewsDetailsResource::make($news));
    }
}
