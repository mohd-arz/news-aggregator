<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\News\GetNewsRequest;
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
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="boolean",
     *             example="true"
     *         ),
     *         description="Search enabled"
     *     ),
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="title"
     *         ),
     *         description="Search by title or description or body"
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="integer", example=1)
     *         ),
     *         style="form",
     *         explode=true,
     *         description="Filter by category"
     *     ),
     *     @OA\Parameter(
     *         name="source",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="integer", example=1)
     *         ),
     *         style="form",
     *         explode=true,
     *         description="Filter by source"
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             format="date",
     *             example="2025-02-16"
     *         ),
     *         description="Filter by date"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="News Fetched Successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/NewsResource")
     *             ),
     *             @OA\Property(
     *                 property="pagination",
     *                 type="object",
     *                 ref="#/components/schemas/Pagination"
     *             )
     *         )
     *    ),
     *   @OA\Response(
     *       response=422,
     *      description="Something went wrong",
     *     @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *  )
     * )
     */

    public function getAllNews(GetNewsRequest $request)
    {
        try {
            $no_of_items = $request->query('per_page', 10);

            $search = $request->query('search', false);

            $news = News::query();

            if ($search) {
                $news = $this->Searchable($news, $request);
            }

            $news = $news->with(['source', 'category'])
                ->orderBy('published_at', 'desc')
                ->paginate($no_of_items);

            return $this->successResponse(
                'News Fetched Successfully',
                NewsResource::collection($news),
                200,
                $this->pagination($news)
            );

        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong', $e->getMessage(), 422);
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
        @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         ),
     *         description="Page number"
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=10
     *         ),
     *         description="Number of items per page"
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="boolean",
     *             example="true"
     *         ),
     *         description="Search enabled"
     *     ),
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="title"
     *         ),
     *         description="Search by title or description or body"
     *     ),
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="integer", example=1)
     *         ),
     *         style="form",
     *         explode=true,
     *         description="Filter by category"
     *     ),
     *     @OA\Parameter(
     *         name="source",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(type="integer", example=1)
     *         ),
     *         style="form",
     *         explode=true,
     *         description="Filter by source"
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             format="date",
     *             example="2025-02-16"
     *         ),
     *         description="Filter by date"
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

    public function ForYou(Request $request)
    {
        try {
            $no_of_items = $request->query('per_page', 10);
            $search = $request->query('search', false);

            /** @var Guard $auth */
            $auth = auth();
            $user = $auth->user();
            $categories = $user->categoryPreferences->pluck('id')->toArray();
            $sources = $user->sourcePreferences->pluck('id')->toArray();
            $authors = $user->authorPreferences->pluck('id')->toArray();

            $news = News::where(function ($query) use ($categories, $sources, $authors) {
                return $query->whereIn('category_id', $categories)
                    ->orWhereIn('source_id', $sources)
                    ->orWhereIn('author_id', $authors);
            });
            
            if ($search) {
                $news = $this->Searchable($news, $request);
            }

            $news = $news->with(['source', 'category', 'author'])
            ->orderBy('published_at', 'desc')
                ->paginate($no_of_items);

            return $this->successResponse(
                'News Fetched Successfully',
                NewsResource::collection($news),
                200,
                $this->pagination($news)
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Something went wrong', $e->getMessage(), 422);
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
    public function getNews(News $news)
    {
        return $this->successResponse('News Fetched Successfully', NewsDetailsResource::make($news));
    }

    private function Searchable($news, Request $request){
        $q = $request->query('q');
        $date = $request->query('date');
        $category = $request->query('category');
        $source = $request->query('source');

        $news = $news->where(function ($query) use ($q) {
            return $query->where('title', 'like', "%$q%")
                ->orWhere('description', 'like', "%$q%")
                ->orWhere('body', 'like', "%$q%");
            })
            ->when($date, function ($query) use ($date) {
                return $query->whereDate('published_at', $date);
            })->when($category, function ($query) use ($category) {
                return $query->whereIn('category_id', $category);
            })->when($source, function ($query) use ($source) {
                return $query->whereIn('source_id', $source);
            });
        
        return $news;
    }
}
