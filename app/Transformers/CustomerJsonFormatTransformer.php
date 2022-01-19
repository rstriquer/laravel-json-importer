<?php

namespace App\Transformers;

use App\Transformers\Contracts\CustomerFileImportTransformerInterface;
use Illuminate\Support\Carbon;
use stdClass;

class CustomerJsonFormatTransformer implements CustomerFileImportTransformerInterface
{
    public function __construct(private CreditCardJsonFormatTransformer $creditCardTransformer)
    {}
    public function transform(stdClass $payload) : array
    {
        $valid_until = null;
        if ($payload->credit_card->expirationDate !== null) {
            $valid_until = app(Carbon::class);
            $valid_until->day = 1;
            $valid_until->month = substr($payload->credit_card->expirationDate, 0, 2);
            $year = substr($payload->credit_card->expirationDate, -2);
            switch ($year>=00) {
                case true:
                    $year = '20' . $year;
                    break;
                default:
                    $year = '19' . $year;
            }
            $valid_until->year = $year;
            $valid_until->addMonth();
            $valid_until = $valid_until->format('Y-m-d');
        }
        if ($payload->date_of_birth!==null) {
            if (strlen($payload->date_of_birth) == 10) {
                $payload->date_of_birth = implode(
                    '-',
                    array_reverse(explode('/',$payload->date_of_birth))
                );
            }
            $payload->date_of_birth = Carbon::parse($payload->date_of_birth)
                ->format('Y-m-d');
        }
        return [
            'name' => $payload->name,
            'address' => $payload->address,
            'checked' => $payload->checked,
            'description' => $payload->description,
            'interest' => $payload->interest,
            'date_of_birth' => $payload->date_of_birth,
            'email' => $payload->email,
            'account' => $payload->account,
            'credit_card' => $this->creditCardTransformer->transform($payload->credit_card),
        ];
    }
}