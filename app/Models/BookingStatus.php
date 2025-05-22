<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    protected $table = 'booking_statuses';
    protected $primaryKey = 'id'; // Menggunakan 'id' sebagai primary key

    protected $fillable = [
        'status_code',
        'display_name',
    ];

    public $timestamps = true;

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'status_id', 'id'); // Relasi menggunakan 'id'
    }
}