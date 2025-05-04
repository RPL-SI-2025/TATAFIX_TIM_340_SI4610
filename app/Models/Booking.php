<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // Menentukan nama tabel jika tidak sesuai dengan konvensi Laravel
    protected $table = 'bookings';

    // Menentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'user_id',           // ID user yang melakukan booking
        'service_id',        // ID layanan yang dibooking
        'nama_pemesan',      // Nama pemesan
        'alamat',            // Alamat pemesan
        'no_handphone',      // Nomor handphone pemesan
        'tanggal_booking',   // Tanggal booking
        'waktu_booking',     // Waktu booking
        'catatan_perbaikan'  // Catatan perbaikan yang dibutuhkan
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relasi dengan Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
