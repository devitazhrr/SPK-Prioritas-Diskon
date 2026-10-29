<?php

namespace App\Filament\Resources\PenilaianResource\Pages;

use App\Filament\Resources\PenilaianResource;
use App\Models\Criteria;
use App\Models\Penilaian;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreatePenilaian extends CreateRecord
{
    protected static string $resource = PenilaianResource::class;

    /**
     * Menyimpan satu alternatif dengan
     * 4 nilai kriteria sekaligus.
     */
    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {

            // Mengambil ID kriteria berdasarkan kode
            $jumlahStok = Criteria::where('kode', 'K1')->firstOrFail();
            $umurStok = Criteria::where('kode', 'K2')->firstOrFail();
            $tingkatPenjualan = Criteria::where('kode', 'K3')->firstOrFail();
            $hargaJual = Criteria::where('kode', 'K4')->firstOrFail();

            // K1 - Jumlah Stok
            $record = Penilaian::create([
                'alternative_id' => $data['alternative_id'],
                'criteria_id' => $jumlahStok->id,
                'nilai' => $data['jumlah_stok'],
            ]);

            // K2 - Umur Stok
            Penilaian::create([
                'alternative_id' => $data['alternative_id'],
                'criteria_id' => $umurStok->id,
                'nilai' => $data['umur_stok'],
            ]);

            // K3 - Tingkat Penjualan
            Penilaian::create([
                'alternative_id' => $data['alternative_id'],
                'criteria_id' => $tingkatPenjualan->id,
                'nilai' => $data['tingkat_penjualan'],
            ]);

            // K4 - Harga Jual
            Penilaian::create([
                'alternative_id' => $data['alternative_id'],
                'criteria_id' => $hargaJual->id,
                'nilai' => $data['harga_jual'],
            ]);

            return $record;
        });
    }
}