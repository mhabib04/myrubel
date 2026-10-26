<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InfoResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Info;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InfoResource extends Resource
{
    protected static ?string $model = Info::class;

    protected static ?string $slug = 'info';

    protected static ?string $modelLabel = 'info';

    protected static ?string $pluralModelLabel = 'Info';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Fields::image('poster', 'Poster', 'info', '4:5 (1080×1350 px)')->columnSpanFull(),
            TextInput::make('title')->label('Judul')->required()->maxLength(255)->columnSpanFull(),
            DatePicker::make('date')->label('Tanggal')->native(false)->displayFormat('j F Y')
                ->helperText('Urutan tampil mengikuti tanggal terbaru. Beranda menampilkan 6 info terbaru.'),
            ...Fields::detailFields('info'),
            Fields::published(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return Fields::table($table, [
            Fields::imageColumn('poster', 'Poster'),
            TextColumn::make('title')->label('Judul')->searchable()->wrap(),
            TextColumn::make('date')->label('Tanggal')->date('j F Y'),
        ], 'date');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInfos::route('/'),
            'create' => Pages\CreateInfo::route('/create'),
            'edit' => Pages\EditInfo::route('/{record}/edit'),
        ];
    }
}
