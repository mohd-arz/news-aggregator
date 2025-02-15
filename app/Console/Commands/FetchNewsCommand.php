<?php

namespace App\Console\Commands;

use App\Services\Api\NewsAggregatorService;
use Illuminate\Console\Command;

class FetchNewsCommand extends Command
{
    protected $newsService;
    public function __construct()
    {
        parent::__construct();
        $this->newsService = new NewsAggregatorService();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching news from API...');
        $this->newsService->storeNews();
        info('News fetched successfully');
    }
}
