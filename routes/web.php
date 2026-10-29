<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\PerhitunganWaspas;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Penilaian;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/laporan-waspas', function () {

    $page = new PerhitunganWaspas();

    $hasil = $page->hitungWaspas();

    $tanggal = Penilaian::max('updated_at');

    $pdf = Pdf::loadView('pdf.hasil-waspas', [
        'hasil' => $hasil,
        'tanggal' => $tanggal,
    ]);

    return $pdf->stream('laporan-waspas.pdf');

})->name('laporan-waspas');