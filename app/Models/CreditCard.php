<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'network',
        'number_crc32',
        'number_first4',
        'number_last4',
        'name',
        'valid_until',
    ];

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id', 'customer_id');
    }
}
