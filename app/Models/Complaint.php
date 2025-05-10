<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'evidence_file',
        'status',
        'admin_notes',
        'validated_by',
        'validated_at'
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    // Relasi dengan user yang membuat pengaduan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan admin yang memvalidasi
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    // Scope untuk filter berdasarkan status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Cek apakah pengaduan memiliki bukti
    public function hasEvidence()
    {
        return !is_null($this->evidence_file);
    }

    // Cek apakah pengaduan bisa divalidasi (status menunggu validasi)
    public function canBeValidated()
    {
        return $this->status === 'menunggu_validasi';
    }
}
