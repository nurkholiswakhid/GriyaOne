<?php

namespace App\Jobs;

use App\Helpers\ImageHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessAssetImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * Timeout in seconds per attempt.
     */
    public int $timeout = 120;

    /**
     * @param array<string> $paths  Storage-relative paths, e.g. ["assets/abc.jpg", ...]
     */
    public function __construct(public readonly array $paths) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->paths as $path) {
            // Skip if file no longer exists (e.g. deleted before job ran)
            if (!Storage::disk('public')->exists($path)) {
                Log::warning('[ProcessAssetImages] File not found, skipping', ['path' => $path]);
                continue;
            }

            try {
                ImageHelper::compressImage($path);
            } catch (\Throwable $e) {
                Log::error('[ProcessAssetImages] compressImage failed', [
                    'path'  => $path,
                    'error' => $e->getMessage(),
                ]);
            }

            try {
                ImageHelper::generateThumbnail($path);
            } catch (\Throwable $e) {
                Log::error('[ProcessAssetImages] generateThumbnail failed', [
                    'path'  => $path,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('[ProcessAssetImages] Job failed', [
            'paths' => $this->paths,
            'error' => $exception->getMessage(),
        ]);
    }
}
