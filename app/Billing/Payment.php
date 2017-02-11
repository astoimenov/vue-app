<?php

namespace App\Billing;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'amount',
        'charge_id',
    ];
}
