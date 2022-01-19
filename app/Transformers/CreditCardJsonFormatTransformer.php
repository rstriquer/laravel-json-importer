<?php

namespace App\Transformers;

use Illuminate\Support\Carbon;
use stdClass;

class CreditCardJsonFormatTransformer
{
    public function transform(stdClass $payload) : array
    {
        $valid_until = null;
        if ($payload->expirationDate !== null) {
            $valid_until = app(Carbon::class);
            $valid_until->day = 1;
            $valid_until->month = substr($payload->expirationDate, 0, 2);
            $valid_until->year = substr($payload->expirationDate, -2);
            $valid_until->addMonth();
        }
        return [
            'network' => $payload->type,
            'number_crc32' => crc32($payload->number),
            'number_first4' => substr($payload->number, 0, 4),
            'number_last4' => substr($payload->number, -4),
            'name' => $payload->name,
            'valid_until' => $valid_until,
        ];
    }
}