<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramResource\Pages;
use App\Filament\Support\Fields;
use App\Filament\Support\ScopedToUnit;
use App\Models\Program;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ProgramResource extends Resource
{
    use ScopedToUnit;

    protected static string $unit = 'bimbel';

    protected static ?string $model = Program::class;

    protected static ?string $slug = 'program';

    protected static ?string $modelLabel = 'program';

    protected static ?string $pluralModelLabel = 'Program';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?int $navigationSort = 1;

    protected static string|UnitEnum|null $navigationGroup = 'My Rubel';

    public static function form(Schema $schema): Schema
    {
        $labels = Program::LABELS[static::$unit];

        return $schema->components([
            static::unitField(),
            Section::make('Kartu di beranda')->columns(2)->schema([
                TextInput::make('name')->label('Nama program')->required()->maxLength(255)->columnSpanFull()
                    ->helperText('Juga menjadi judul halaman detail.'),
                TextInput::make('short_desc')->label('Deskripsi singkat')->required()->maxLength(255)->columnSpanFull()
                    ->helperText('Tampil di kartu beranda.'),
                Fields::image('image', 'Gambar', 'programs', '4:5 (1080×1350 px)')->columnSpanFull(),
            ]),
            Section::make('Halaman detail')->columns(2)->schema([
                TextInput::make('schedule')->label('Jadwal')->maxLength(255),
                TextInput::make('subjects')->label($labels['subjects'])->maxLength(255),
                TextInput::make('price')->label('Harga')->numeric()->minValue(0)->prefix('Rp')
                    ->helperText('Tanpa titik. Kosongkan jika belum ditentukan.'),
                TextInput::make('price_unit')->label('Satuan harga')->required()->default($labels['price_unit'])->prefix('/'),
                Repeater::make('features')->label($labels['features'])
                    ->simple(TextInput::make('item')->required()->maxLength(255))
                    ->addActionLabel('Tambah poin')
                    ->helperText('Tampil sebagai daftar poin di halaman detail.')
                    ->defaultItems(0)
                    ->columnSpanFull(),
            ]),
            Fields::published(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return Fields::table($table, [
            Fields::imageColumn('image', 'Gambar'),
            TextColumn::make('name')->label('Nama')->searchable(),
            TextColumn::make('price')->label('Harga')
                ->formatStateUsing(fn ($state) => 'Rp'.number_format($state, 0, ',', '.')),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'edit' => Pages\EditProgram::route('/{record}/edit'),
        ];
    }
}
