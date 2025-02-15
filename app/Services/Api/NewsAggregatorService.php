<?php

namespace App\Services\Api;

use App\Models\Author;
use App\Models\Category;
use App\Models\News;
use App\Models\Source;
use App\Services\Api\NewsStrategy\NewsApiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NewsAggregatorService
{
  protected $apiStrategies;
  public function __construct()
  {
    $this->apiStrategies = [
      'newsapi' => new NewsApiService(),
    ];
  }

  /**
   * Summary of fetchNews
   * Fetch news from the provider
   * @param string $provdier
   * @return mixed
   * @throws \Exception
   */
  public function fetch($provider)
  {
    if (!array_key_exists($provider, $this->apiStrategies)) {
      throw new \Exception('Provider not found');
    }
    // Fetch news from the provider
    return $this->apiStrategies[$provider]->fetchNews();
  }

  /**
   * Summary of storeNews
   * Store news in database
   * @return void
   */
  public function storeNews()
  {
    foreach ($this->apiStrategies as $provider => $service) {
      $news = $this->fetch($provider);
      if ($news['status'] !== 'ok') {
        continue;
      }
      $articles = $news['articles'];
      foreach ($articles as $data) {
        $this->saveNews($data, $provider);
      }
    }
  }

  /**
   * Summary of saveNews
   * Save news in database
   * @param mixed $data
   * @param string $provider
   * @return void
   */
  private function saveNews($data, $provider): void
  {
    try {
      DB::beginTransaction();
      $category = Category::firstOrCreate(['name' => $data['category'] ?? 'General']);
      $author = Author::firstOrCreate(['name' => $data['author'] ?? 'Unknown']);
      $source = Source::firstOrCreate(['name' => $data['source'] ?? 'Unknown']);
      News::updateOrCreate(
        ['url' => $data['url']],
        [
          'source_id' => $source->id,
          'title' => $data['title'],
          'description' => $data['description'],
          'url' => $data['url'],
          'image_url' => $data['urlToImage'],
          'published_at' => Carbon::parse($data['publishedAt'])->toDateTimeString(),
          'category_id' => $category->id,
          'author_id' => $author->id,
          'providers' => $provider
        ]
      );
      DB::commit();
    } catch (\Exception $e) {
      info($e);
      DB::rollBack();
    }
  }
}
