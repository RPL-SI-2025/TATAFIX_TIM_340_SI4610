<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    protected $table = 'booking_statuses';
    protected $primaryKey = 'id';

    protected $fillable = [
        'status_code',
        'display_name',
    ];

    public $timestamps = true;

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'booking_status_id');
    }

    /*
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    * @return \App\Models\BookingLog
    */
    public function bookingLogs()
    {
        return $this->hasMany(BookingLog::class);
    }
}
