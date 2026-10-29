<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CriteriaResource\Pages;
use App\Filament\Resources\CriteriaResource\RelationManagers;
use App\Models\Criteria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class CriteriaResource extends Resource
{
    // Model yang digunakan pada Resource ini
    protected static ?string $model = Criteria::class;

    // Icon yang ditampilkan pada sidebar
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    // Pengaturan nama menu pada sidebar
    protected static ?string $navigationLabel = 'Data Kriteria';
    protected static ?string $pluralModelLabel = 'Data Kriteria';
    protected static ?string $modelLabel = 'Kriteria';
    protected static ?string $navigationGroup = 'Manajemen Data';
    protected static ?int $navigationSort = 2;

    /**
     * Form untuk menambah dan mengubah data kriteria
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                // Input kode kriteria
                TextInput::make('kode')
                    ->label('Kode Kriteria')
                    ->required()
                    ->unique(ignoreRecord: true),

                // Input nama kriteria
                TextInput::make('nama')
                    ->label('Nama Kriteria')
                    ->required(),

                // Input bobot kriteria
                TextInput::make('bobot')
                    ->label('Bobot')
                    ->numeric()
                    ->required()
                    ->step(0.01),

                // Memilih jenis kriteria (Benefit atau Cost)
                Select::make('tipe')
                    ->label('Jenis Kriteria')
                    ->options([
                        'benefit' => 'Benefit',
                        'cost' => 'Cost',
                    ])
                    ->required(),
            ]);
    }

    /**
     * Menampilkan daftar data kriteria
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // Kolom kode kriteria
                TextColumn::make('kode')
                    ->label('Kode'),

                // Kolom nama kriteria
                TextColumn::make('nama')
                    ->label('Nama Kriteria'),

                // Kolom bobot kriteria
                TextColumn::make('bobot')
                    ->label('Bobot'),

                // Menampilkan jenis kriteria dengan warna berbeda
                BadgeColumn::make('tipe')
                    ->colors([
                        'success' => 'benefit',
                        'danger' => 'cost',
                    ]),
            ])

            // Tempat filter data (belum digunakan)
            ->filters([
                //
            ])

            // Aksi pada setiap data
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])

            // Aksi untuk beberapa data sekaligus
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Relasi Resource (belum digunakan)
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * Routing halaman pada Resource
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCriterias::route('/'),
            'create' => Pages\CreateCriteria::route('/create'),
            'edit' => Pages\EditCriteria::route('/{record}/edit'),
        ];
    }
}