<?php

use App\Adapters\GuardianAdapter;
use App\Adapters\NewsApiAdapter;
use App\Adapters\NYTimesAdapter;
use App\Jobs\FetchArticlesJob;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    FetchArticlesJob::dispatch(NewsApiAdapter::class);
    FetchArticlesJob::dispatch(GuardianAdapter::class);
    FetchArticlesJob::dispatch(NYTimesAdapter::class);
})
    ->hourly()
    ->name('fetch-articles')
    ->withoutOverlapping()
    ->onOneServer(); // if there is multiple servers
