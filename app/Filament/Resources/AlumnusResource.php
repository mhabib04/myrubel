<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlumnusResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Alumnus;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AlumnusResource extends Resource
{
    protected static ?string $model = Alumnus::class;

    protected static ?string $slug = 'alumni';

    protected static ?string $modelLabel = 'alumni';

    protected static ?string $pluralModelLabel = 'Alumni';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?int $navigationSort = 3;

    protected static string|UnitEnum|null $navigationGroup = 'My Rubel';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Fields::image('photo', 'Foto', 'alumni', '4:5 (1080×1350 px)')->columnSpanFull(),
            TextInput::make('name')->label('Nama alumni')->required()->maxLength(255),
            TextInput::make('graduation_year')->label('Tahun lulus')->numeric()->minValue(2000)->maxValue(2100)
                ->helperText('Urutan tampil mengikuti tahun terbaru.'),
            TextInput::make('campus')->label('Kampus/Sekolah')->maxLength(255),
            TextInput::make('major')->label('Jurusan')->maxLength(255),
            Fields::published(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return Fields::table($table, [
            Fields::imageColumn('photo', 'Foto'),
            TextColumn::make('name')->label('Nama')->searchable(),
            TextColumn::make('graduation_year')->label('Tahun lulus'),
            TextColumn::make('campus')->label('Kampus/Sekolah'),
            TextColumn::make('major')->label('Jurusan'),
        ], 'graduation_year');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAlumni::route('/'),
            'create' => Pages\CreateAlumnus::route('/create'),
            'edit' => Pages\EditAlumnus::route('/{record}/edit'),
        ];
    }
}
