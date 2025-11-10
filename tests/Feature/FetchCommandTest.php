<?php

use App\Jobs\FetchArticlesJob;
use Illuminate\Support\Facades\Artisan;

test('fetch articles command works', function () {
    Queue::fake();

    Artisan::call('articles:fetch');

    Queue::assertPushed(FetchArticlesJob::class, 3);
});

test('fetch articles command can target specific source', function () {
    Queue::fake();

    Artisan::call('articles:fetch', ['--source' => 'newsapi']);

    Queue::assertPushed(FetchArticlesJob::class, 1);
});
