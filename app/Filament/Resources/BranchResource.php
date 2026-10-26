<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BranchResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Branch;
use BackedEnum;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $slug = 'cabang';

    protected static ?string $modelLabel = 'cabang';

    protected static ?string $pluralModelLabel = 'Cabang';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?int $navigationSort = 9;

    protected static string|UnitEnum|null $navigationGroup = 'My Rubel';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            TextInput::make('name')->label('Nama cabang')->required()->maxLength(255),
            Select::make('type')->label('Jenis')->required()->native(false)->default('Cabang')
                ->options(array_combine(Branch::TYPES, Branch::TYPES)),
            TextInput::make('address')->label('Alamat')->maxLength(255)->columnSpanFull(),
            Textarea::make('maps_query')->label('Peta Google Maps')->rows(3)->maxLength(5000)->columnSpanFull()
                ->helperText('Dari Google Maps: Bagikan → Sematkan peta → Salin HTML, lalu tempel di sini. Bisa juga diisi koordinat (mis. 0.1234, 99.8765) atau nama tempat.')
                ->rule(fn () => function (string $attribute, $value, Closure $fail) {
                    $value = trim((string) $value);
                    $looksLikeCode = str_contains($value, '<') || str_starts_with($value, 'http');
                    if ($looksLikeCode && ! Branch::extractEmbedUrl($value)) {
                        $fail('Kode peta tidak dikenali. Salin dari Google Maps → Bagikan → Sematkan peta → Salin HTML.');
                    }
                }),
            TextInput::make('whatsapp')->label('Nomor WhatsApp')->tel()->maxLength(20)
                ->helperText('Kosongkan untuk memakai nomor WhatsApp utama.'),
            Toggle::make('is_coming_soon')->label('Segera hadir')->inline(false)
                ->helperText('Ditampilkan ringkas, tanpa peta dan tombol WhatsApp.'),
            Fields::published(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return Fields::table($table, [
            TextColumn::make('name')->label('Nama')->searchable(),
            TextColumn::make('type')->label('Jenis')->badge(),
            TextColumn::make('address')->label('Alamat')->wrap(),
            TextColumn::make('whatsapp')->label('WhatsApp'),
            IconColumn::make('is_coming_soon')->label('Segera hadir')->boolean(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }
}
