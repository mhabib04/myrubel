<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AchievementResource\Pages;
use App\Filament\Support\Fields;
use App\Filament\Support\ScopedToUnit;
use App\Models\Achievement;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class AchievementResource extends Resource
{
    use ScopedToUnit;

    protected static string $unit = 'bimbel';

    protected static ?string $model = Achievement::class;

    protected static ?string $slug = 'prestasi';

    protected static ?string $modelLabel = 'prestasi';

    protected static ?string $pluralModelLabel = 'Prestasi';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static ?int $navigationSort = 2;

    protected static string|UnitEnum|null $navigationGroup = 'My Rubel';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            static::unitField(),
            Fields::image('poster', 'Poster', 'prestasi', '4:5 (1080×1350 px)')->columnSpanFull(),
            TextInput::make('title')->label('Nama lomba & peringkat')->required()->maxLength(255)->columnSpanFull(),
            Select::make('level')->label('Tingkat')->required()->native(false)
                ->options(array_combine(Achievement::LEVELS, Achievement::LEVELS)),
            TextInput::make('student_name')->label('Nama siswa')->maxLength(255),
            TextInput::make('year')->label('Tahun')->numeric()->minValue(2000)->maxValue(2100)
                ->helperText('Urutan tampil mengikuti tahun terbaru.'),
            Fields::published(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return Fields::table($table, [
            Fields::imageColumn('poster', 'Poster'),
            TextColumn::make('title')->label('Lomba & peringkat')->searchable()->wrap(),
            TextColumn::make('level')->label('Tingkat')->badge(),
            TextColumn::make('student_name')->label('Siswa'),
            TextColumn::make('year')->label('Tahun'),
        ], 'year');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievement::route('/create'),
            'edit' => Pages\EditAchievement::route('/{record}/edit'),
        ];
    }
}
