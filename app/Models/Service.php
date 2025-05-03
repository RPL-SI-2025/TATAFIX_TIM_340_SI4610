<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'service_id';

    protected $fillable = [
        'provider_id', 'title_service', 'description', 'category_id',
        'base_price', 'label_unit', 'image_url', 'availbility', 'rating_avg'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function parameters()
    {
        return $this->hasMany(ServiceParameter::class, 'service_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'service_id');
    }
}
