<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenilaianResource\Pages;
use App\Models\Penilaian;
use App\Models\Criteria;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class PenilaianResource extends Resource
{
    protected static ?string $model = Penilaian::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Data Penilaian';
    protected static ?string $pluralModelLabel = 'Data Penilaian';
    protected static ?string $modelLabel = 'Penilaian';
    protected static ?string $navigationGroup = 'Perhitungan';
    protected static ?int $navigationSort = 1;

    /**
     * Form untuk mengisi nilai 4 kriteria sekaligus.
     */
    public static function form(Form $form): Form
{
    return $form
        ->schema([

            // Memilih alternatif
            Select::make('alternative_id')
                ->label('Alternatif')
                ->relationship('alternative', 'nama')
                ->required(),

            // K1 - Jumlah Stok
            TextInput::make('jumlah_stok')
                ->label('Jumlah Stok (Meter)')
                ->numeric()
                ->required(),

            // K2 - Umur Stok
            TextInput::make('umur_stok')
                ->label('Umur Stok (Bulan)')
                ->numeric()
                ->required(),

            // K3 - Tingkat Penjualan
            TextInput::make('tingkat_penjualan')
                ->label('Tingkat Penjualan (Meter)')
                ->numeric()
                ->required(),

            // K4 - Harga Jual
            TextInput::make('harga_jual')
                ->label('Harga Jual (Per Meter)')
                ->numeric()
                ->required(),
        ]);
}

    /**
     * Menampilkan satu baris untuk setiap alternatif.
     *
     * Database tetap menyimpan 4 record penilaian
     * untuk setiap alternatif.
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('penilaians')
                    ->groupBy('alternative_id');
            });
    }

    /**
     * Menampilkan data penilaian dalam bentuk matriks.
     */
public static function table(Table $table): Table
{
    return $table
        ->columns([

            // Alternatif
            TextColumn::make('alternative.nama')
                ->label('Alternatif')
                ->extraHeaderAttributes([
                    'style' => 'vertical-align: middle;'
                ]),

            // K1 - Jumlah Stok
            TextColumn::make('jumlah_stok')
                ->label(new HtmlString(
                    '<div style="
                        text-align: center;
                        line-height: 1.4;
                        white-space: nowrap;
                    ">
                        <div>Jumlah Stok</div>
                        <div style="font-weight: normal;">(Meter)</div>
                    </div>'
                ))
                ->alignCenter()
                ->extraHeaderAttributes([
                    'style' => 'vertical-align: middle;'
                ])
                ->state(function ($record) {

                    $criteriaId = Criteria::where('kode', 'K1')
                        ->value('id');

                    return Penilaian::where(
                        'alternative_id',
                        $record->alternative_id
                    )
                        ->where('criteria_id', $criteriaId)
                        ->value('nilai') ?? '-';
                }),

            // K2 - Umur Stok
            TextColumn::make('umur_stok')
                ->label(new HtmlString(
                    '<div style="
                        text-align: center;
                        line-height: 1.4;
                        white-space: nowrap;
                    ">
                        <div>Umur Stok</div>
                        <div style="font-weight: normal;">(Bulan)</div>
                    </div>'
                ))
                ->alignCenter()
                ->extraHeaderAttributes([
                    'style' => 'vertical-align: middle;'
                ])
                ->state(function ($record) {

                    $criteriaId = Criteria::where('kode', 'K2')
                        ->value('id');

                    return Penilaian::where(
                        'alternative_id',
                        $record->alternative_id
                    )
                        ->where('criteria_id', $criteriaId)
                        ->value('nilai') ?? '-';
                }),

            // K3 - Tingkat Penjualan
            TextColumn::make('tingkat_penjualan')
                ->label(new HtmlString(
                    '<div style="
                        text-align: center;
                        line-height: 1.4;
                        white-space: nowrap;
                    ">
                        <div>Tingkat Penjualan</div>
                        <div style="font-weight: normal;">(Meter)</div>
                    </div>'
                ))
                ->alignCenter()
                ->extraHeaderAttributes([
                    'style' => 'vertical-align: middle;'
                ])
                ->state(function ($record) {

                    $criteriaId = Criteria::where('kode', 'K3')
                        ->value('id');

                    return Penilaian::where(
                        'alternative_id',
                        $record->alternative_id
                    )
                        ->where('criteria_id', $criteriaId)
                        ->value('nilai') ?? '-';
                }),

            // K4 - Harga Jual
TextColumn::make('harga_jual')
    ->label(new HtmlString(
        '<div style="
            text-align: center;
            line-height: 1.4;
            white-space: nowrap;
        ">
            <div>Harga Jual</div>
            <div style="font-weight: normal;">(Per Meter)</div>
        </div>'
    ))
    ->alignCenter()
    ->extraHeaderAttributes([
        'style' => 'vertical-align: middle;'
    ])
    ->state(function ($record) {

        $criteriaId = Criteria::where('kode', 'K4')
            ->value('id');

        $nilai = Penilaian::where(
            'alternative_id',
            $record->alternative_id
        )
            ->where('criteria_id', $criteriaId)
            ->value('nilai');

        return $nilai !== null
            ? 'Rp' . number_format($nilai, 0, ',', '.')
            : '-';
    }),

            // Terakhir Diperbarui
            TextColumn::make('updated_at')
                ->label('Terakhir Diperbarui')
                ->alignCenter()
                ->extraHeaderAttributes([
                    'style' => 'vertical-align: middle;'
                ])
                ->state(function ($record) {

                    return Penilaian::where(
                        'alternative_id',
                        $record->alternative_id
                    )->max('updated_at');

                })
                ->date('d-m-Y')
                ->sortable(),

        ])

        ->filters([
            //
        ])

        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])

        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenilaians::route('/'),
            'create' => Pages\CreatePenilaian::route('/create'),
            'edit' => Pages\EditPenilaian::route('/{record}/edit'),
        ];
    }
}