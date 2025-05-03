<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // jika pakai login (optional)

class Tukang extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address', 'role_id', 'photo'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function getRouteKeyName()
    {
        return 'user_id';
    }
}
