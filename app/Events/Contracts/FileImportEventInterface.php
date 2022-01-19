<?php

namespace App\Events\Contracts;

/**
 * File import event contract
 */
interface FileImportEventInterface
{
    /**
     * Returns the name of the file being imported
     */
    public function getFileFormat() : string;
    /**
     * Returns the format of the file being imported
     */
    public function getFileName() : string;
}
