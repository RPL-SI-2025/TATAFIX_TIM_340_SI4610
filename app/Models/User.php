<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'password',
        'photo',
        'status',
        'is_verified'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_verified' => 'boolean',
    ];
    
    /**
     * Get the tukang's availability status.
     * A tukang is considered available if they are active and verified.
     *
     * @return bool
     */
    public function getIsAvailableAttribute()
    {
        // Tukang dianggap tersedia jika statusnya aktif dan sudah diverifikasi email
        return $this->status === 'active' && $this->email_verified_at !== null;
    }
    
    /**
     * Get the bookings assigned to this user (as a tukang).
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'assigned_worker_id');
    }
    
    /**
     * Get the count of completed bookings for this user (tukang).
     *
     * @return int
     */
    public function getCompletedBookingsCountAttribute()
    {
        // Jika relasi bookings ada, hitung jumlah booking yang selesai
        // Ini perlu disesuaikan dengan struktur database yang sebenarnya
        if (method_exists($this, 'bookings')) {
            return $this->bookings()
                ->whereHas('status', function($query) {
                    $query->where('status_code', 'completed');
                })
                ->count();
        }
        
        return 0;
    }
}