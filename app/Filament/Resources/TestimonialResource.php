<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Filament\Support\Fields;
use App\Filament\Support\ScopedToUnit;
use App\Models\Testimonial;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class TestimonialResource extends Resource
{
    use ScopedToUnit;

    protected static string $unit = 'bimbel';

    protected static ?string $model = Testimonial::class;

    protected static ?string $slug = 'testimoni';

    protected static ?string $modelLabel = 'testimoni';

    protected static ?string $pluralModelLabel = 'Testimoni';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?int $navigationSort = 4;

    protected static string|UnitEnum|null $navigationGroup = 'My Rubel';

    public static function form(Schema $schema): Schema
    {
        $labels = Testimonial::LABELS[static::$unit];

        return $schema->columns(2)->components([
            static::unitField(),
            Fields::image('photo', 'Foto', 'testimoni', '4:5 (1080×1350 px)')->columnSpanFull(),
            TextInput::make('name')->label('Nama')->required()->maxLength(255),
            TextInput::make('year')->label($labels['year'])->numeric()->minValue(2000)->maxValue(2100)
                ->helperText('Urutan tampil mengikuti tahun terbaru.'),
            TextInput::make('graduated_to')->label($labels['graduated_to'])->maxLength(255)->columnSpanFull()
                ->helperText($labels['graduated_to_help']),
            Textarea::make('quote')->label('Testimoni')->required()->rows(4)->maxLength(500)->columnSpanFull()
                ->helperText('Maks. 500 karakter.'),
            Fields::published(),
        ]);
    }

    public static function table(Table $table): Table
    {
        $labels = Testimonial::LABELS[static::$unit];

        return Fields::table($table, [
            Fields::imageColumn('photo', 'Foto'),
            TextColumn::make('name')->label('Nama')->searchable(),
            TextColumn::make('year')->label($labels['year']),
            TextColumn::make('graduated_to')->label($labels['graduated_to'])->wrap(),
            TextColumn::make('quote')->label('Testimoni')->limit(60)->wrap(),
        ], 'year');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
