<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingLog extends Model
{
    /** @use HasFactory<\Database\Factories\BookingLogFactory> */
    use HasFactory;

    /*
    * @var array<int, string>
    */
    protected $fillable = [
        'status_id',
        'booking_id',
    ];

    /*
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    * @return \App\Models\Booking
    */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /*
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    * @return \App\Models\BookingStatus
    */
    public function bookingStatus()
    {
        return $this->belongsTo(BookingStatus::class);
    }
}
