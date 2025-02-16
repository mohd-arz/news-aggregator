<?php

namespace App\Services\Api;

use App\Jobs\ProcessNews;
use App\Models\Author;
use App\Models\Category;
use App\Models\News;
use App\Models\Source;
use App\Services\Api\NewsStrategy\GuardianApiService;
use App\Services\Api\NewsStrategy\NewsApiService;
use App\Services\Api\NewsStrategy\NYTApiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NewsAggregatorService
{
  protected $apiStrategies;
  public function __construct()
  {
    $this->apiStrategies = [
      'newsapi' => new NewsApiService(),
      'guardian' => new GuardianApiService(),
      'nyt' => new NYTApiService(),
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
      if(!isset($news['articles'])){
        continue;
      }
      $articles = $news['articles'];
      ProcessNews::dispatch($articles,$provider);
    }
  }
}
