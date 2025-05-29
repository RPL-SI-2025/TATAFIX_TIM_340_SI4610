<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'booking_id',
        'user_id',
        'nama_pemesan',
        'no_handphone',
        'alamat',
        'jenis_layanan',
        'down_payment',
        'biaya_pelunasan',
        'total',
        'status',
        'tanggal_invoice',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function generateInvoiceNumber()
    {
        $lastInvoice = $this->orderBy('id', 'desc')->first();
        $number = $lastInvoice ? intval(substr($lastInvoice->invoice_number, 1)) + 1 : 735866;
        return '#' . $number;
    }
}
