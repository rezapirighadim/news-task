<?php

namespace App\Jobs;

use App\Contracts\NewsSourceInterface;
use App\Services\AggregationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchArticlesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [60, 300, 900]; // 1min, 5min, 15min
    public $timeout = 120;

    public function __construct(
        private string $adapterClass
    ) {}

    public function handle(AggregationService $service): void
    {
        try {
            $adapter = app($this->adapterClass);

            if (!$adapter instanceof NewsSourceInterface) {
                Log::error("Invalid adapter class: {$this->adapterClass}");
                return;
            }

            $service->fetchFromSource($adapter);

        } catch (\Exception $e) {
            Log::error("Job failed for {$this->adapterClass}", [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Job permanently failed for {$this->adapterClass}", [
            'error' => $exception->getMessage()
        ]);
    }
}
