<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'payment_method',
        'amount',
        'status',
        'proof_of_payment',
        'payment_notes'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
