<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pemesan',
        'alamat',
        'no_handphone',
        'tanggal_booking',
        'waktu_booking',
        'catatan_perbaikan',
    ];
}