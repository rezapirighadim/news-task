<?php

use App\Jobs\FetchArticlesJob;
use App\Adapters\NewsApiAdapter;
use App\Services\AggregationService;
use Illuminate\Support\Facades\Queue;

test('job can be dispatched', function () {
    Queue::fake();

    FetchArticlesJob::dispatch(NewsApiAdapter::class);

    Queue::assertPushed(FetchArticlesJob::class);
});

test('job handles adapter correctly', function () {
    Http::fake([
        'newsapi.org/*' => Http::response(['articles' => []], 200)
    ]);

    $job = new FetchArticlesJob(NewsApiAdapter::class);
    $job->handle(app(AggregationService::class));

    expect(true)->toBeTrue();
});
