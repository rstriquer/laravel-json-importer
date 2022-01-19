<?php

namespace App\Jobs;

use App\Events\Contracts\FileImportEventInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

abstract class AbstractFileImportJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected LoggerInterface $log;

    /**
     * @param string $channel Sets log channel name to be used
     * @return void
     */
    public function __construct(string $channel)
    {
        $this->log = Log::channel($channel);
    }
    /**
     * Execute the job.
     * @return void
     */
    abstract function handle(FileImportEventInterface $event);
}
