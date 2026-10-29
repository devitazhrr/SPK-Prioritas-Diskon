<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // Menggunakan fitur autentikasi, factory, dan notifikasi Laravel
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Field yang boleh diisi saat proses tambah atau ubah data
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Field yang disembunyikan saat data dikirim atau ditampilkan
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mengubah tipe data field tertentu secara otomatis
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}