<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingParameter extends Model
{
    protected $table = 'booking_parameters';
    protected $primaryKey = 'id';

    protected $fillable = [
        'booking_id',
        'parameter_id',
        'input_value',
        'note',
    ];

    public $timestamps = true;

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function parameter()
    {
        return $this->belongsTo(ServiceParameter::class, 'parameter_id');
    }
}
