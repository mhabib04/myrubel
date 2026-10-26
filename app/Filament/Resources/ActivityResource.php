<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Activity;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivityResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $slug = 'event';

    protected static ?string $modelLabel = 'event';

    protected static ?string $pluralModelLabel = 'Event';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Fields::image('photo', 'Foto', 'kegiatan', '4:5 (1080×1350 px)')->columnSpanFull(),
            TextInput::make('title')->label('Nama event')->required()->maxLength(255)->columnSpanFull(),
            DatePicker::make('date')->label('Tanggal')->native(false)->displayFormat('j F Y')
                ->helperText('Urutan tampil mengikuti tanggal terbaru.'),
            TextInput::make('place')->label('Tempat')->maxLength(255),
            ...Fields::detailFields('kegiatan'),
            Fields::published(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return Fields::table($table, [
            Fields::imageColumn('photo', 'Foto'),
            TextColumn::make('title')->label('Event')->searchable()->wrap(),
            TextColumn::make('date')->label('Tanggal')->date('j F Y'),
            TextColumn::make('place')->label('Tempat'),
        ], 'date');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}
