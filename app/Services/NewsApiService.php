<?php

namespace App\Services;

use DateTime;
use App\Enums\NewsSource;
use App\Interfaces\NewsApiInterface;
use Illuminate\Support\Facades\Http;

class NewsApiService implements NewsApiInterface
{
    private $apiKey;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->apiKey = config('services.newsapi.key');
    }

    public function fetchNewsArticles(): array
    {
        $response = Http::get('https://newsapi.org/v2/top-headlines', [
            'apiKey' => $this->apiKey,
            'language' => 'en',
        ]);

        return $response->json()['articles'] ?? [];
    }


    public function parseNewsArticles($newsArticles): array
    {
        return array_map(function ($newsArticle) {
            return [
                'url' => $newsArticle['url'],
                'title' => $newsArticle['title'],
                'description' => $newsArticle['description'],
                'content' => $newsArticle['content'],
                'author' => $newsArticle['author'],
                'imageUrl' => $newsArticle['urlToImage'],
                'source' => $newsArticle['source']['name'],
                'category' => $newsArticle['category'] ?? 'unclassified',
                'published_at' => (new DateTime($newsArticle['publishedAt']))->format('Y-m-d H:i:s'),
            ];
        }, $newsArticles);
    }
}
