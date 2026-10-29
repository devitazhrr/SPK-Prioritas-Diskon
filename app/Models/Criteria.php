<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Criteria extends Model
{
    use HasFactory;

    // Field yang boleh diisi saat proses tambah atau ubah data
    protected $fillable = [
        'kode',
        'nama',
        'bobot',
        'tipe',
    ];

    /**
     * Validasi agar total bobot seluruh kriteria
     * tidak melebihi 1 (100%)
     */
    protected static function booted()
    {
        static::saving(function ($model) {

            // Menghitung total bobot yang sudah tersimpan
            $total = Criteria::sum('bobot');

            // Jika data sedang diedit,
            // kurangi bobot lama terlebih dahulu
            if ($model->exists) {
                $total -= $model->getOriginal('bobot');
            }

            // Tambahkan bobot yang baru diinput
            $total += $model->bobot;

            // Jika total bobot melebihi 1 maka simpan data dibatalkan
            if ($total > 1) {
                throw ValidationException::withMessages([
                    'bobot' => 'Total bobot tidak boleh lebih dari 1 (100%)',
                ]);
            }
        });
    }

    /**
     * Relasi satu kriteria memiliki banyak data penilaian
     */
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
}