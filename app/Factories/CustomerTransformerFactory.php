<?php

namespace App\Factories;

use App\Transformers\Contracts\CustomerFileImportTransformerInterface;
use App\Transformers\CustomerJsonFormatTransformer;
use InvalidArgumentException;

class CustomerTransformerFactory
{
    /**
     * Determine which transformer should be used
     * @return array
     * @throws InvalidArgumentException
     */
    public static function create(string $fileFormat) : CustomerFileImportTransformerInterface
    {
        switch($fileFormat) {
            case 'json': return app(CustomerJsonFormatTransformer::class);
        }

        throw new InvalidArgumentException('Invalid importer format');
    }
}
