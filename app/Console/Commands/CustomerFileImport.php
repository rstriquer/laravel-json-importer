<?php

namespace App\Console\Commands;

use App\Events\CustomerFileImportEvent;
use App\Jobs\CustomerFileImportJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;

class CustomerFileImport extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'import:customerFile '
        . '{fileName : Physical path and name of the file containing the information to be imported} '
        . '{format? : File format to consider. Today "json" is the only format. Default: "json"}';
    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Import customer data file into database';
    private LoggerInterface $log;

    /**
     * Create a new command instance.
     * - Set log channel to the CustomerFileImportJob::CHANNEL definition
     * @see CustomerFileImportJob::CHANNEL
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->log = Log::channel(CustomerFileImportJob::CHANNEL);
    }
    /**
     * Transforms command line received parameters into $argv format
     */
    private function buildParams() : array
    {
        return [
            'format' => $this->argument('format') ?: 'json',
            'fileName' => $this->argument('fileName'),
        ];
    }
    /**
     * Execute the console command.
     * @return int
     */
    public function handle()
    {
        $argv = $this->buildParams();
        $this->log->info('Dispatching import demand ' . $argv['format'] . ' file ' . $argv['fileName']);
        CustomerFileImportEvent::dispatch($argv['format'], $argv['fileName']);
    }
}
