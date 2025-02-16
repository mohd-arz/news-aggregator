<?

namespace App\Services\Api\NewsStrategy;


use App\Interface\Api\NewsFetchInterface;
use App\Trait\ResponseTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NewsApiService implements NewsFetchInterface
{
  protected $url;
  protected $apiKey;
  public function __construct()
  {
    $this->url = config('news-api.newsapi.url');
    $this->apiKey = config('news-api.newsapi.api_key');
  }

  // Fetch news from News API and normalize the response and return
  public function fetchNews()
  {
    try{
      $response = Http::get($this->url, [
        'apiKey' => $this->apiKey,
        'country' => 'us',
      ]);
  
      if($response->json()['status'] !== 'ok'){
        return $response->json();
      }
  
      $result['status']= $response->json()['status'];
      $result['articles'] = collect($response->json()['articles'])->map(function ($article) {
        return [
            'title' => $article['title'],
            'description' => $article['description'],
            'body' => Str::limit($article['content'] ?? '', 200),
            'url' => $article['url'] ?? null,
            'urlToImage' => $article['urlToImage'] ?? null,
            'publishedAt' => $article['publishedAt'],
            'source' => $article['source']['name'] ?? 'Unknown',
            'author' => $article['author'] ?? 'Unknown',
            'category' => $this->detectCategory($article['title'] ?? '', $article['description'] ?? '')
        ];
    });   
     
      return $result;
    }catch(\Exception $e){
      return $e;
    }
  }

  
  // Because of News API limitation, we need to categorize the news using AI
  public function categorizeWithAI($text)
    {
    $response = Http::post("https://api.openai.com/v1/classifications", [
        "model" => "text-davinci-003",
        "query" => $text,
        "labels" => ["Business", "Sports", "Technology", "Entertainment", "Health", "Science", "Politics"],
        "api_key" => config('services.openai.key'),
    ]);

    return $response->json()['label'] ?? 'General';
    }

    // Detect category based on title and description , because of News API limitation
    public function detectCategory($title, $description)
    {
        $categories = [
            'business' => ['stock', 'market', 'economy', 'finance', 'IPO'],
            'sports' => ['football', 'cricket', 'NBA', 'tennis', 'FIFA', 'Olympics'],
            'technology' => ['AI', 'blockchain', 'startup', 'software', 'tech'],
            'entertainment' => ['movie', 'celebrity', 'Hollywood', 'Bollywood'],
            'health' => ['COVID', 'vaccine', 'cancer', 'fitness'],
            'science' => ['NASA', 'quantum', 'physics', 'biology'],
            'politics' => ['election', 'government', 'senate', 'minister'],
        ];
    
        foreach ($categories as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (stripos($title, $keyword) !== false || stripos($description, $keyword) !== false) {
                    return ucfirst($category);
                }
            }
        }
    
        return 'General'; 
    }
}