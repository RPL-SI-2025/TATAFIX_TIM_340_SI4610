<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceParameter extends Model
{
    protected $table = 'service_parameters';
    protected $primaryKey = 'id';

    protected $fillable = [
        'service_id',
        'name',
        'unit',
        'price_per_unit',
    ];

    public $timestamps = true;

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function bookings()
    {
        return $this->hasMany(BookingParameter::class, 'parameter_id');
    }
}
