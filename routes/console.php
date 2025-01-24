<?php

use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\FetchNewsApiCommand;
use App\Console\Commands\FetchGuardianNews;
use App\Console\Commands\FetchNewYorkTimes;


Schedule::command(FetchGuardianNews::class)->everyFifteenMinutes();
Schedule::command(FetchNewsApiCommand::class)->everyFifteenMinutes();
Schedule::command(FetchNewYorkTimes::class)->everyFifteenMinutes();