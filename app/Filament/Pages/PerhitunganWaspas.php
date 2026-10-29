<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Penilaian;

class PerhitunganWaspas extends Page
{
    // Icon yang ditampilkan pada menu sidebar
    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    // Tampilan halaman yang digunakan
    protected static string $view = 'filament.pages.perhitungan-waspas';

    // Pengaturan nama menu dan halaman
    protected static ?string $navigationLabel = 'Hasil Perhitungan';
    protected static ?string $pluralModelLabel = 'Hasil Perhitungan';
    protected static ?string $title = 'Hasil Akhir Perhitungan';
    protected static ?string $navigationGroup = 'Perhitungan';
    protected static ?int $navigationSort = 2;


    /**
     * Tombol Cetak PDF pada bagian atas halaman
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetakPdf')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->url(route('laporan-waspas'))
                ->openUrlInNewTab(),
        ];
    }


    /**
     * Mengambil seluruh data penilaian
     * lalu menyusunnya menjadi matriks keputusan (Xij).
     */
    public function getMatriks()
    {
        $alternatives = Alternative::all();
        $criteria = Criteria::all();

        $data = [];

        // Jika belum ada alternatif atau kriteria,
        // kembalikan matriks kosong agar tidak terjadi error.
        if ($alternatives->isEmpty() || $criteria->isEmpty()) {
            return [];
        }

        foreach ($alternatives as $alt) {

            foreach ($criteria as $crit) {

                // Mengambil nilai setiap alternatif
                // berdasarkan kriteria.
                $nilai = Penilaian::where('alternative_id', $alt->id)
                    ->where('criteria_id', $crit->id)
                    ->value('nilai');

                // Jika nilai belum tersedia,
                // dianggap 0.
                $data[$alt->id][$crit->id] = $nilai ?? 0;
            }
        }

        return $data;
    }


    /**
     * Melakukan normalisasi matriks
     * sesuai jenis kriteria Benefit atau Cost.
     */
    public function normalisasi()
    {
        $alternatives = Alternative::all();
        $criteria = Criteria::all();

        // Jika belum ada alternatif atau kriteria,
        // tidak perlu melakukan normalisasi.
        if ($alternatives->isEmpty() || $criteria->isEmpty()) {
            return [];
        }

        $matriks = $this->getMatriks();

        // Jika matriks kosong, kembalikan hasil kosong.
        if (empty($matriks)) {
            return [];
        }

        $hasil = [];


        foreach ($criteria as $crit) {

            $values = [];


            // Mengumpulkan seluruh nilai
            // pada setiap kriteria.
            foreach ($alternatives as $alt) {

                // Pastikan data tersedia di dalam matriks.
                $values[] = $matriks[$alt->id][$crit->id] ?? 0;
            }


            // Jika tidak ada nilai,
            // lewati kriteria tersebut.
            if (empty($values)) {
                continue;
            }


            // Mencari nilai terbesar dan terkecil.
            $max = max($values);
            $min = min($values);


            // Menghindari pembagian dengan angka 0.
            if ($max == 0) {
                $max = 1;
            }

            if ($min == 0) {
                $min = 1;
            }


            foreach ($alternatives as $alt) {

                // Mengambil nilai Xij.
                $xij = $matriks[$alt->id][$crit->id] ?? 0;


                // Jika kriteria Benefit,
                // menggunakan rumus Xij / Max.
                if ($crit->tipe == 'benefit') {

                    $hasil[$alt->id][$crit->id] = $xij / $max;

                } else {

                    // Jika kriteria Cost,
                    // menggunakan rumus Min / Xij.
                    if ($xij == 0) {

                        // Jika nilai Xij = 0,
                        // hasil normalisasi dibuat 0
                        // agar tidak terjadi pembagian dengan 0.
                        $hasil[$alt->id][$crit->id] = 0;

                    } else {

                        $hasil[$alt->id][$crit->id] = $min / $xij;
                    }
                }
            }
        }


        return $hasil;
    }


    /**
     * Menghitung nilai akhir metode WASPAS
     * kemudian menentukan urutan ranking.
     */
    public function hitungWaspas()
    {
        // Mengambil seluruh alternatif dan kriteria.
        $alternatives = Alternative::all();
        $criteria = Criteria::all();


        // Jika belum ada alternatif atau kriteria,
        // tidak melakukan perhitungan.
        if ($alternatives->isEmpty() || $criteria->isEmpty()) {
            return [];
        }


        // Memastikan total bobot kriteria = 1.
        $totalBobot = Criteria::sum('bobot');


        // Jika total bobot belum 1,
        // kembalikan hasil kosong agar halaman
        // tidak mengalami error.
        if (round($totalBobot, 5) != 1) {
            return [];
        }


        // Mengambil hasil normalisasi.
        $normal = $this->normalisasi();


        // Jika hasil normalisasi kosong,
        // kembalikan hasil kosong.
        if (empty($normal)) {
            return [];
        }


        $result = [];


        foreach ($alternatives as $alt) {

            // Nilai awal WSM dan WPM.
            $wsm = 0;
            $wpm = 1;


            foreach ($criteria as $crit) {

                // Mengambil nilai hasil normalisasi.
                $xij = $normal[$alt->id][$crit->id] ?? 0;

                // Mengambil bobot kriteria.
                $w = $crit->bobot;


                // Menghitung nilai WSM.
                $wsm += $xij * $w;


                // Menghitung nilai WPM.
                $wpm *= pow($xij, $w);
            }


            // Menghitung nilai preferensi (Qi).
            $qi = (0.5 * $wsm) + (0.5 * $wpm);


            // Menyimpan hasil setiap alternatif.
            $result[] = [
                'alternatif' => $alt->nama,
                'qi' => $qi,
            ];
        }


        // Mengurutkan nilai Qi
        // dari terbesar ke terkecil.
        usort($result, function ($a, $b) {

            return $b['qi'] <=> $a['qi'];
        });


        // Memberikan nomor ranking.
        $rank = 1;


        foreach ($result as &$row) {

            $row['rank'] = $rank++;
        }


        return $result;
    }
}