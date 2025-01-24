<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Services\NewsService;
use Illuminate\Console\Command;
use App\Services\NewYorkTimesService;

class FetchNewYorkTimes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-new-york-times';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(NewYorkTimesService $newsApiService, NewsService $newsService)
    {
        //
        $newsArticles = $newsApiService->fetchNewsArticles();

        $parsedNewsArticles = $newsApiService->parseNewsArticles($newsArticles);

        foreach ($parsedNewsArticles as $key => $article) {
            $newsService->update($article);
        }

        $this->info('News articles fetched and stored successfully.');

    }
}
