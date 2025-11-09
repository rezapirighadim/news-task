<?php

namespace App\Console\Commands;

use App\Adapters\GuardianAdapter;
use App\Adapters\NewsApiAdapter;
use App\Adapters\NYTimesAdapter;
use App\Jobs\FetchArticlesJob;
use Illuminate\Console\Command;

class FetchArticles extends Command
{
    protected $signature = 'articles:fetch {--source=all : Specify source: newsapi, guardian, nytimes, or all}';

    protected $description = 'Fetch articles from news sources';

    public function handle(): int
    {
        $source = $this->option('source');

        $sources = match($source) {
            'newsapi' => [NewsApiAdapter::class],
            'guardian' => [GuardianAdapter::class],
            'nytimes' => [NYTimesAdapter::class],
            default => [
                NewsApiAdapter::class,
                GuardianAdapter::class,
                NYTimesAdapter::class
            ]
        };

        foreach ($sources as $adapterClass) {
            $this->info("Dispatching job for {$adapterClass}");
            FetchArticlesJob::dispatch($adapterClass);
        }

        $this->info('Jobs dispatched successfully!');

        return self::SUCCESS;
    }
}
