<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CreditCardFactory extends Factory
{
    public function definition()
    {
        $expirationDate = $this->faker->creditCardExpirationDate;
        $valid_until = null;
        if ($expirationDate !== null) {
            $valid_until = app(Carbon::class);
            $valid_until->day = $expirationDate->format('d');
            $valid_until->month = $expirationDate->format('m');
            $valid_until->year = $expirationDate->format('Y');
            $valid_until->addMonth();
        }
        $return = [
            'customer_id' => $this->faker->numberBetween(1,999),
            'network' => $this->faker->creditCardType,
            'number_crc23' => null,
            'number_first4' => $this->faker->creditCardNumber,
            'number_last4' => null,
            'name' => $this->faker->name(),
            'valid_until' => $valid_until,
        ];
        $return['number_crc23'] = crc32($return['number_first4']);
        $return['number_last4'] = substr($return['number_first4'], -4);
        $return['number_first4'] = substr($return['number_first4'], 0, 4);
        return $return;
    }
}