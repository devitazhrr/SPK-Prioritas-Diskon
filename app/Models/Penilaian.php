<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    // Field yang boleh diisi saat proses tambah atau ubah data
    protected $fillable = [
        'alternative_id',
        'criteria_id',
        'nilai',
    ];

    /**
     * Relasi bahwa setiap data penilaian
     * dimiliki oleh satu alternatif
     */
    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }

    /**
     * Relasi bahwa setiap data penilaian
     * dimiliki oleh satu kriteria
     */
    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}