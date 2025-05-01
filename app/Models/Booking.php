<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Service;
use App\Models\BookingStatus;
use App\Models\BookingParameter;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';
    protected $primaryKey = 'booking_id';

    protected $fillable = [
        'user_id',
        'service_id',
        'status_id',
        'total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function status()
    {
        return $this->belongsTo(BookingStatus::class, 'status_id');
    }

    public function parameters()
    {
        return $this->hasMany(BookingParameter::class, 'booking_id');
    }
}