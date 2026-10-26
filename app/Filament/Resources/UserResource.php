<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

/**
 * Kelola akun admin. Hanya super admin (Bu Lina, satu-satunya) yang bisa membuka halaman ini.
 * Akun yang dibuat di sini selalu berperan admin. Akun tidak dihapus, cukup dinonaktifkan.
 */
class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'akun';

    protected static ?string $modelLabel = 'akun admin';

    protected static ?string $pluralModelLabel = 'Akun Admin';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?int $navigationSort = 11;

    protected static string|UnitEnum|null $navigationGroup = 'Akun';

    public static function canViewAny(): bool
    {
        return (bool) auth()->user()?->isSuperAdmin();
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canViewAny() && $record->role === 'admin';
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    /** Super admin tidak ikut tampil; profilnya sendiri diubah lewat menu Profil. */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'admin');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')->label('Nama')->required()->maxLength(255),
            TextInput::make('username')->label('Username')->required()->alphaDash()->maxLength(50)
                ->unique(ignoreRecord: true)
                ->helperText('Untuk login. Huruf, angka, - atau _, tanpa spasi.'),
            TextInput::make('password')->label('Password')->password()->revealable()
                ->minLength(8)->maxLength(255)
                ->required(fn (string $operation) => $operation === 'create')
                ->dehydrated(fn (?string $state) => filled($state))
                ->helperText(fn (string $operation) => $operation === 'edit'
                    ? 'Kosongkan jika tidak diganti.'
                    : 'Minimal 8 karakter.'),
            Toggle::make('is_active')->label('Akun aktif')->default(true)->inline(false)
                ->helperText('Nonaktifkan jika admin tidak lagi bertugas. Akun nonaktif tidak dapat login.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('username')->label('Username')->searchable(),
                ToggleColumn::make('is_active')->label('Aktif'),
                TextColumn::make('created_at')->label('Dibuat')->date('j F Y'),
            ])
            ->defaultSort('created_at')
            ->paginated(false)
            ->emptyStateHeading('Belum ada akun admin')
            ->emptyStateDescription('Klik "Buat akun admin" untuk menambahkan admin.')
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
