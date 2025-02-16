<?

namespace App\Services\Api\NewsStrategy;

use App\Interface\Api\NewsFetchInterface;
use Illuminate\Support\Facades\Http;

class GuardianApiService implements NewsFetchInterface
{
  protected $url;
  protected $apiKey;
  public function __construct()
  {
    $this->url = config('news-api.guardian.url');
    $this->apiKey = config('news-api.guardian.api_key');
  }

 
  public function fetchNews()
  {
    try{
      $response = Http::get($this->url, [
        'api-key' => $this->apiKey,
        'show-fields' => 'all',
      ]);
  
      if(!$response->json()['response'] || $response->json()['response']['status'] !== 'ok'){
        return $response->json();
      }
  
      $result['status']= $response->json()['response']['status'];
      $result['articles'] = collect($response->json()['response']['results'])->map(function ($article) {
        return [
            'title' => $article['webTitle'],
            'description' => $article['fields']['trailText'] ?? $article['webTitle'],
            'body' => $article['fields']['body'] ?? '',
            'url' => $article['webUrl'] ?? null,
            'urlToImage' => null,
            'publishedAt' => $article['webPublicationDate'],
            'source' => 'The Guardian',
            'author' => $article['fields']['byline'] ?? 'Unknown',
            'category' => $article['sectionName'] ?? 'General'
        ];
    });   
     
      return $result;
    }catch(\Exception $e){
      return $e;
    }
  }
}
