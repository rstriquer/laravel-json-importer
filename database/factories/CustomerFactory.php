<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
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
            'name' => $this->faker->name(),
            'address' => $this->faker->numberBetween(1,999) . ' '
                . $this->faker->streetName . ' ' . $this->faker->city . ' '
                . $this->faker->state,
            'checked' => $this->faker->boolean,
            'description' => $this->faker->realText(),
            'interest' => $this->faker->words(),
            'date_of_birth' => now(),
            'email' => $this->faker->unique()->safeEmail(),
            'account' => $this->faker->numberBetween(999999, 999999999999),
            'credit_card_customer_id' => $this->faker->numberBetween(1,999),
            'credit_card_network' => $this->faker->creditCardType,
            'credit_card_number_crc32' => null,
            'credit_card_number_first4' => $this->faker->creditCardNumber,
            'credit_card_number_last4' => null,
            'credit_card_name' => $this->faker->name(),
            'credit_card_valid_until' => $valid_until,
        ];
        $return['number_crc32'] = crc32($return['number_first4']);
        $return['number_last4'] = substr($return['number_first4'], -4);
        $return['number_first4'] = substr($return['number_first4'], 0, 4);

        return $return;
    }
}