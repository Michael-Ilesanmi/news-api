<?php

namespace App\Console\Commands;

use App\Models\News;
use Illuminate\Console\Command;
use App\Services\GuardianNewsService;
use App\Services\NewsService;

class FetchGuardianNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-guardian-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(GuardianNewsService $service, NewsService $newsService)
    {
        //
        $newsArticles = $service->fetchNewsArticles();

        $parsedNewsArticles = $service->parseNewsArticles($newsArticles);

        foreach ($parsedNewsArticles as $key => $article) {
            $newsService->update($article);
        }

        $this->info('News articles fetched and stored successfully.');
    }
}
