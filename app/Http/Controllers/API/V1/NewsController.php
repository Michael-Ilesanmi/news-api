<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsRequest;
use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    public function __construct(private NewsService $service)
    {
        // 
    }

    public function index(NewsRequest $request): JsonResponse
    {
        $news = $this->service->search($request);

        return response()->json($news);
    }


    public function show($id)
    {
        $news = News::findOrFail($id);
        return response()->json($news);
    }
}
