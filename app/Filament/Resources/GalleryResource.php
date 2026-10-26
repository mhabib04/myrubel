<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Gallery;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static ?string $slug = 'galeri';

    protected static ?string $modelLabel = 'foto galeri';

    protected static ?string $pluralModelLabel = 'Galeri';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?int $navigationSort = 7;

    protected static string|UnitEnum|null $navigationGroup = 'My Rubel';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Fields::image('image', 'Foto', 'galeri', '16:9 (1920×1080 px)')->required()->columnSpanFull(),
            TextInput::make('caption')->label('Keterangan')->maxLength(255)->columnSpanFull()
                ->helperText('Opsional. Tampil di bagian bawah foto.'),
            Fields::published(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return Fields::table($table, [
            Fields::imageColumn('image', 'Foto'),
            TextColumn::make('caption')->label('Keterangan')->searchable()->wrap(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}
