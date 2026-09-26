<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false; // tabel hanya punya created_at, tanpa updated_at

    protected $fillable = [
        'nama',
        'email',
        'password',
        'nim',
        'no_whatsapp',
        'alamat',
        'kota',
        'kode_pos',
        'foto_ktm',
        'role',
        'status_verifikasi',
        'status_aktif',
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'status_aktif' => 'boolean',
            'created_at' => 'datetime',
            'terakhir_online' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVerified(): bool
    {
        return $this->status_verifikasi === 'terverifikasi';
    }
}
