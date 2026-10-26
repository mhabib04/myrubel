<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Work;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

/** Karya siswa Literacy Center: puisi, cerpen, essay, artikel. */
class WorkResource extends Resource
{
    protected static ?string $model = Work::class;

    protected static ?string $slug = 'literacy/karya';

    protected static ?string $modelLabel = 'karya';

    protected static ?string $pluralModelLabel = 'Karya';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;

    protected static ?int $navigationSort = 3;

    protected static string|UnitEnum|null $navigationGroup = 'Literacy Center';

    private static function typeOptions(): array
    {
        return array_map(fn ($type) => $type[0], Work::TYPES);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Select::make('type')->label('Jenis karya')->required()->native(false)
                ->options(static::typeOptions()),
            DatePicker::make('date')->label('Tanggal')->native(false)->displayFormat('j F Y')
                ->helperText('Urutan tampil mengikuti tanggal terbaru.'),
            TextInput::make('title')->label('Judul')->required()->maxLength(255)->columnSpanFull(),
            TextInput::make('author')->label('Penulis')->maxLength(255)->columnSpanFull()
                ->helperText('Tampil sebagai "Karya oleh ...".'),
            Fields::image('poster', 'Poster', 'karya', '4:5 (1080×1350 px)')->columnSpanFull(),
            RichEditor::make('content')->label('Isi karya')->columnSpanFull()
                ->toolbarButtons([['bold', 'italic', 'underline', 'link'], ['h2', 'h3', 'bulletList', 'orderedList', 'blockquote'], ['undo', 'redo']])
                ->helperText('Beberapa kata pertama tampil sebagai ringkasan di daftar karya.'),
            Fields::published(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return Fields::table($table, [
            Fields::imageColumn('poster', 'Poster'),
            TextColumn::make('title')->label('Judul')->searchable()->wrap(),
            TextColumn::make('type')->label('Jenis')->badge()
                ->formatStateUsing(fn (string $state) => Work::label($state)),
            TextColumn::make('author')->label('Penulis')->searchable(),
            TextColumn::make('date')->label('Tanggal')->date('j F Y'),
        ], 'date')->filters([
            SelectFilter::make('type')->label('Jenis karya')->options(static::typeOptions()),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorks::route('/'),
            'create' => Pages\CreateWork::route('/create'),
            'edit' => Pages\EditWork::route('/{record}/edit'),
        ];
    }
}
