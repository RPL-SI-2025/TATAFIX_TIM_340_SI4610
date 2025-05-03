<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // Menentukan nama tabel jika tidak sesuai dengan konvensi Laravel
    protected $table = 'bookings';

    // Menentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'nama_pemesan',      // Nama pemesan
        'alamat',            // Alamat pemesan
        'no_handphone',      // Nomor handphone pemesan
        'catatan_perbaikan'  // Catatan perbaikan yang dibutuhkan
    ];

    /**
     * Jika ada relasi, Anda bisa mendefinisikannya di sini.
     * Contoh:
     * public function user()
     * {
     *     return $this->belongsTo(User::class);
     * }
     */
}
