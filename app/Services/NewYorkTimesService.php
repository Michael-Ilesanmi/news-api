<?php

namespace App\Services;

use DateTime;
use App\Enums\NewsSource;
use App\Interfaces\NewsApiInterface;
use Illuminate\Support\Facades\Http;

class NewYorkTimesService implements NewsApiInterface
{
    private $apiKey;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->apiKey = config('services.new_york_times.key');
    }

    public function fetchNewsArticles(): array
    {
        // https://api.nytimes.com/svc/search/v2/articlesearch.json?q=election&api-key=qpSjvauAYmzAlt9z5WBqkrGGchtQnZnw

        $response = Http::get('https://api.nytimes.com/svc/search/v2/articlesearch.json', [
            'api-key' => $this->apiKey,
            'q' => ''
        ]);

        return $response->json()['response']['docs'] ?? [];
    }


    public function parseNewsArticles($newsArticles): array
    {
        return array_map(function ($newsArticle) {
            return [
                'url' => $newsArticle['web_url'],
                'title' => $newsArticle['headline']['main'],
                'description' => $newsArticle['abstract'],
                'content' => NULL,
                'author' => $newsArticle['byline']['original'],
                'imageUrl' => $newsArticle['multimedia'][0]['url'] ?? NULL,
                'source' => $newsArticle['source'],
                'category' => $newsArticle['news_desk'] ?? 'unclassified',
                'published_at' => (new DateTime($newsArticle['pub_date']))->format('Y-m-d H:i:s'),
            ];
        }, $newsArticles);
    }
}
