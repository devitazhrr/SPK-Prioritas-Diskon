<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    // Model yang digunakan pada Resource ini
    protected static ?string $model = User::class;

    // Icon yang ditampilkan pada sidebar
    protected static ?string $navigationIcon = 'heroicon-o-user';

    // Pengaturan nama menu pada sidebar
    protected static ?string $navigationLabel = 'Akun Pengguna';
    protected static ?string $pluralModelLabel = 'Akun Pengguna';
    protected static ?string $modelLabel = 'Akun';
    protected static ?string $navigationGroup = 'Manajemen Pengguna';
    protected static ?int $navigationSort = 3;

    /**
     * Form untuk menambah dan mengubah akun pengguna
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                // Input nama pengguna
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                // Input email pengguna
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),

                // Input password
                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()

                    // Password wajib diisi saat membuat akun baru
                    ->required(fn ($context) => $context === 'create')

                    // Password disimpan dalam bentuk terenkripsi
                    ->dehydrateStateUsing(fn ($state) =>
                        filled($state) ? bcrypt($state) : null
                    )

                    // Password hanya diperbarui jika diisi
                    ->dehydrated(fn ($state) => filled($state))
                    ->maxLength(255),
            ]);
    }

    /**
     * Menampilkan daftar akun pengguna
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // Menampilkan nama pengguna
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                // Menampilkan email pengguna
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                // Menampilkan tanggal akun dibuat
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}