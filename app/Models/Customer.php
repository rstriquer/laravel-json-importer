<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'checked',
        'description',
        'interest',
        'date_of_birth',
        'email',
        'account',
    ];

    protected $casts = [
        'credit_card_valid_until' => 'datetime:Y-m-d',
        'date_of_birth' => 'datetime:Y-m-d',
    ];

    public function creditCard() : HasOne
    {
        return $this->hasOne(CreditCard::class, 'customer_id', 'id');
    }
}
