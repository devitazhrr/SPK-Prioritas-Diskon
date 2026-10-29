<?php

namespace App\Filament\Resources\PenilaianResource\Pages;

use App\Filament\Resources\PenilaianResource;
use App\Models\Criteria;
use App\Models\Penilaian;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditPenilaian extends EditRecord
{
    protected static string $resource = PenilaianResource::class;

    /**
     * Mengambil nilai 4 kriteria
     * sebelum ditampilkan pada form edit.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $alternativeId = $this->record->alternative_id;

        $data['alternative_id'] = $alternativeId;

        // K1 - Jumlah Stok
        $criteriaId = Criteria::where('kode', 'K1')->value('id');

        $data['jumlah_stok'] = Penilaian::where(
            'alternative_id',
            $alternativeId
        )
            ->where('criteria_id', $criteriaId)
            ->value('nilai') ?? '';

        // K2 - Umur Stok
        $criteriaId = Criteria::where('kode', 'K2')->value('id');

        $data['umur_stok'] = Penilaian::where(
            'alternative_id',
            $alternativeId
        )
            ->where('criteria_id', $criteriaId)
            ->value('nilai') ?? '';

        // K3 - Tingkat Penjualan
        $criteriaId = Criteria::where('kode', 'K3')->value('id');

        $data['tingkat_penjualan'] = Penilaian::where(
            'alternative_id',
            $alternativeId
        )
            ->where('criteria_id', $criteriaId)
            ->value('nilai') ?? '';

        // K4 - Harga Jual
        $criteriaId = Criteria::where('kode', 'K4')->value('id');

        $data['harga_jual'] = Penilaian::where(
            'alternative_id',
            $alternativeId
        )
            ->where('criteria_id', $criteriaId)
            ->value('nilai') ?? '';

        return $data;
    }

    /**
     * Memperbarui 4 nilai kriteria sekaligus.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        DB::transaction(function () use ($record, $data) {

            $alternativeId = $record->alternative_id;

            // K1 - Jumlah Stok
            $criteriaId = Criteria::where('kode', 'K1')->value('id');

            Penilaian::updateOrCreate(
                [
                    'alternative_id' => $alternativeId,
                    'criteria_id' => $criteriaId,
                ],
                [
                    'nilai' => $data['jumlah_stok'],
                ]
            );

            // K2 - Umur Stok
            $criteriaId = Criteria::where('kode', 'K2')->value('id');

            Penilaian::updateOrCreate(
                [
                    'alternative_id' => $alternativeId,
                    'criteria_id' => $criteriaId,
                ],
                [
                    'nilai' => $data['umur_stok'],
                ]
            );

            // K3 - Tingkat Penjualan
            $criteriaId = Criteria::where('kode', 'K3')->value('id');

            Penilaian::updateOrCreate(
                [
                    'alternative_id' => $alternativeId,
                    'criteria_id' => $criteriaId,
                ],
                [
                    'nilai' => $data['tingkat_penjualan'],
                ]
            );

            // K4 - Harga Jual
            $criteriaId = Criteria::where('kode', 'K4')->value('id');

            Penilaian::updateOrCreate(
                [
                    'alternative_id' => $alternativeId,
                    'criteria_id' => $criteriaId,
                ],
                [
                    'nilai' => $data['harga_jual'],
                ]
            );
        });

        return $record->refresh();
    }

    /**
     * Tombol hapus pada halaman edit.
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}