<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanTempUploads extends Command
{
    protected $signature   = 'temp:clean {--hours=2 : Delete temp files older than this many hours}';
    protected $description = 'Delete temporary upload files older than the given number of hours';

    public function handle(): int
    {
        $hours     = (int) $this->option('hours');
        $threshold = Carbon::now()->subHours($hours)->getTimestamp();
        $disk      = Storage::disk('public');
        $deleted   = 0;

        // Clean temp root files
        foreach ($disk->files('temp') as $file) {
            if ($disk->lastModified($file) < $threshold) {
                $disk->delete($file);
                $deleted++;
            }
        }

        // Clean temp/thumbs files
        foreach ($disk->files('temp/thumbs') as $file) {
            if ($disk->lastModified($file) < $threshold) {
                $disk->delete($file);
                $deleted++;
            }
        }

        $this->info("Deleted {$deleted} temp file(s) older than {$hours} hour(s).");
        return Command::SUCCESS;
    }
}
