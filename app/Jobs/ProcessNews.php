<?php

namespace App\Jobs;

use App\Models\Author;
use App\Models\Category;
use App\Models\News;
use App\Models\Source;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class ProcessNews implements ShouldQueue
{
    use Queueable;

    protected $articles;
    protected $provider;

    // public $tries = 3;
    /**
     * Create a new job instance.
     */
    public function __construct($articles, $provider)
    {
        $this->articles = $articles;
        $this->provider = $provider;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->articles as $data) {
            $this->saveNews($data);
        }
    }

    /**
     * Summary of saveNews
     * Save news in database
     * @param mixed $data
     * @return void
     */
    private function saveNews($data): void
    {
        try {
            DB::beginTransaction();
            $category = Category::firstOrCreate(['name' => $data['category'] ?? 'General']);
            $author = Author::firstOrCreate(['name' => $data['author'] ?? 'Unknown']);
            $source = Source::firstOrCreate(['name' => $data['source'] ?? 'Unknown']);

            $existingNews = News::where('url', $data['url'])
                ->orWhere('title', $data['title'])
                ->exists();

            if (!$existingNews) {
                News::create(
                    [
                        'source_id' => $source->id,
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'body' => $data['body'],
                        'url' => $data['url'],
                        'image_url' => $data['urlToImage'],
                        'published_at' => Carbon::parse($data['publishedAt'])->toDateTimeString(),
                        'category_id' => $category->id,
                        'author_id' => $author->id,
                        'providers' => $this->provider
                    ]
                );
            }
            DB::commit();
        } catch (\Exception $e) {
            info($e);
            DB::rollBack();
            throw $e;
        }
    }
}
