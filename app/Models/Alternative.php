<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    use HasFactory;

    // Field yang boleh diisi saat proses tambah atau ubah data
    protected $fillable = [
        'kode',
        'nama',
    ];

    /**
     * Relasi satu alternatif memiliki banyak data penilaian
     */
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
}