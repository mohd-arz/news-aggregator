<?php

return [
    'newsapi' => [
        'name' => 'newsapi',
        'url' => 'https://newsapi.org/v2/top-headlines',
        'api_key' => env('NEWSAPI_KEY'),
    ],
    'guardian' => [
      'name' => 'guardian',
      'url' => 'https://content.guardianapis.com/search',
      'api_key' => env('GUARDIAN_KEY'),
    ],
    'nyt' => [
        'name' => 'nyt',
        'url' => 'https://api.nytimes.com/svc/search/v2/articlesearch.json',
        'api_key' => env('NYT_KEY'),
    ],
];
