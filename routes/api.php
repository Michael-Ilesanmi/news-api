<?php

use App\Http\Controllers\API\V1\NewsController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return "News Aggregator Backend";
});

Route::get('/search', [NewsController::class, 'index']);
