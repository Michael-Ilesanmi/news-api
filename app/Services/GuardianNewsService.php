<?php

namespace App\Services;

use App\Enums\NewsSource;
use DateTime;
use App\Interfaces\NewsApiInterface;
use Illuminate\Support\Facades\Http;

class GuardianNewsService implements NewsApiInterface
{
    private $apiKey;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->apiKey = config('services.guardian.key');
    }

    public function fetchNewsArticles(): array
    {
        // https://content.guardianapis.com/search?api-key=437e430c-f959-41db-a50b-fe4ddee0cc60

        $response = Http::get('https://content.guardianapis.com/search', [
            'api-key' => $this->apiKey,
            'page-size' => 50
        ]);

        return $response->json()['response']['results'] ?? [];
    }


    public function parseNewsArticles($newsArticles): array
    {
        return array_map(function ($newsArticle) {
            return [
                'url' => $newsArticle['webUrl'],
                'title' => $newsArticle['webTitle'],
                'description' => NULL,
                'content' => NULL,
                'author' => NULL,
                'imageUrl' => NULL,
                'source' => NewsSource::GUARDIAN->value,
                'category' => $newsArticle['sectionName'] ?? 'unclassified',
                'published_at' => (new DateTime($newsArticle['webPublicationDate']))->format('Y-m-d H:i:s'),
            ];
        }, $newsArticles);
    }
}
