<?php

namespace App\Services\Api\NewsStrategy;

use App\Interface\Api\NewsFetchInterface;
use Illuminate\Support\Facades\Http;

class NYTApiService implements NewsFetchInterface
{
  protected $url;
  protected $apiKey;
  public function __construct()
  {
    $this->url = config('news-api.nyt.url');
    $this->apiKey = config('news-api.nyt.api_key');
  }

  // Fetch news from News API and normalize the response and return
  public function fetchNews()
  {
    try {
      $response = Http::get($this->url, [
        'api-key' => $this->apiKey,
      ]);

      if (!$response->json()['response'] || $response->json()['status'] !== 'OK') {
        return $response->json();
      }

      $result['status'] = $response->json()['status'] === 'OK' ? 'ok' : $response->json()['status'];
      $result['articles'] = collect($response->json()['response']['docs'])->map(function ($article) {
        return [
          'title' => $article['headline']['main'] ?? $article['abstract'],
          'description' => $article['abstract'],
          'body' => $article['lead_paragraph'] ?? '',
          'url' => $article['web_url'] ?? null,
          'urlToImage' => $article['multimedia'][0]['url'] ?? null,
          'publishedAt' => $article['pub_date'],
          'source' => $article['source'] ?? 'Unknown',
          // Author is returning with By prefix.
          'author' => isset($article['byline']['original']) ? str_replace('By ', '', $article['byline']['original']) : 'Unknown',
        ];
      });

      return $result;
    } catch (\Exception $e) {
      return $e;
    }
  }
}
