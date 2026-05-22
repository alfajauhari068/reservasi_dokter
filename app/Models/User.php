<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens; // ✔ benar
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_DOKTER = 'dokter';
    const ROLE_PASIEN = 'pasien';

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isDokter()
    {
        return $this->role === self::ROLE_DOKTER;
    }

    public function isPasien()
    {
        return $this->role === self::ROLE_PASIEN;
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}


