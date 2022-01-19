<?php

namespace App\Events;

use App\Events\Contracts\FileImportEventInterface;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event must be called whenever a customer file import demand occurs.
 * - to call use as below
 * CustomerFileImportEvent::dispatch($format, $fileName);
 */
class CustomerFileImportEvent implements FileImportEventInterface
{
    use Dispatchable, SerializesModels;

    /**
     * Create the event listener.
     * @param string $fileFormat Should be 'json' for now
     * @param string $fileName
     * @return void
     */
    public function __construct(private string $fileFormat, private string $fileName)
    { }
    /**
     * @inheritDoc
     */
    public function getFileFormat() : string
    {
        return $this->fileFormat;
    }
    /**
     * @inheritDoc
     */
    public function getFileName() : string
    {
        return $this->fileName;
    }
}
