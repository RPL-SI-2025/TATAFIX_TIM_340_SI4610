<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    protected $table = 'booking_statuses';
    // Update the primary key if it's different in the database
    protected $primaryKey = 'id'; // Assuming 'id' is the primary key

    protected $fillable = [
        'customer', // Nama Pelanggan
        'email',    // Email
        'status',   // Status
    ];

    public $timestamps = true;

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'status_id');
    }
    
    public function index()
    {
        $bookings = BookingStatus::all();
        return view('pages.admin.status-booking.index', compact('bookings'));
    }
}
