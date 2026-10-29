<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlternativeResource\Pages;
use App\Filament\Resources\AlternativeResource\RelationManagers;
use App\Models\Alternative;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class AlternativeResource extends Resource
{
    // Model yang digunakan pada Resource ini
    protected static ?string $model = Alternative::class;

    // Icon yang ditampilkan pada sidebar
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    // Pengaturan nama menu pada sidebar
    protected static ?string $navigationLabel = 'Data Alternatif';
    protected static ?string $pluralModelLabel = 'Data Alternatif';
    protected static ?string $modelLabel = 'Alternatif';
    protected static ?string $navigationGroup = 'Manajemen Data';
    protected static ?int $navigationSort = 1;

    /**
     * Form untuk menambah dan mengubah data alternatif
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                // Input kode alternatif
                TextInput::make('kode')
                    ->label('Kode Alternatif')
                    ->required()
                    ->unique(ignoreRecord: true),

                // Input nama alternatif
                TextInput::make('nama')
                    ->label('Nama Alternatif')
                    ->required(),
            ]);
    }

    /**
     * Menampilkan daftar data alternatif
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // Kolom kode alternatif
                TextColumn::make('kode')
                    ->label('Kode')
                    ->searchable(),

                // Kolom nama alternatif
                TextColumn::make('nama')
                    ->label('Nama Alternatif')
                    ->searchable(),
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
            'index' => Pages\ListAlternatives::route('/'),
            'create' => Pages\CreateAlternative::route('/create'),
            'edit' => Pages\EditAlternative::route('/{record}/edit'),
        ];
    }
}