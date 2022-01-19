<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditCardsTable extends Migration
{
    public function up()
    {
        Schema::create('credit_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->string('network')->comment('The card network name. e.g. Visa, Mastercard');
            $table->string('number_crc32')->comment('Used to identify card uniqueness. Saves the crc32 hash of the full card number');
            $table->smallInteger('number_first4')->comment('The credit card number. Only saves the first 4 digits.');
            $table->smallInteger('number_last4')->comment('The credit card number. Only saves the latest 4 digits.');
            $table->string('name');
            $table->date('valid_until');
            $table->timestamps();

            $table->unique('number_crc32');
        });
    }
    public function down()
    {
        Schema::dropIfExists('credit_cards');
    }
}
