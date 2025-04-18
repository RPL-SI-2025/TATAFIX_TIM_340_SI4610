<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'nama_pemesan',
        'alamat',
        'no_handphone',
        'catatan_perbaikan'
    ];
}