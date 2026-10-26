<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Teacher;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static ?string $slug = 'pengajar';

    protected static ?string $modelLabel = 'pengajar';

    protected static ?string $pluralModelLabel = 'Pengajar';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?int $navigationSort = 8;

    protected static string|UnitEnum|null $navigationGroup = 'My Rubel';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Fields::image('photo', 'Foto', 'pengajar', '4:5 (1080×1350 px)')->columnSpanFull(),
            TextInput::make('name')->label('Nama')->required()->maxLength(255),
            TextInput::make('subject')->label('Bidang mata pelajaran')->maxLength(255),
            TextInput::make('almamater')->label('Alumni kampus')->maxLength(255),
            TextInput::make('motto')->label('Motto')->maxLength(150)
                ->helperText('Maks. 150 karakter.'),
            Fields::published(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return Fields::table($table, [
            Fields::imageColumn('photo', 'Foto'),
            TextColumn::make('name')->label('Nama')->searchable(),
            TextColumn::make('subject')->label('Bidang'),
            TextColumn::make('almamater')->label('Alumni kampus')->wrap(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachers::route('/'),
            'create' => Pages\CreateTeacher::route('/create'),
            'edit' => Pages\EditTeacher::route('/{record}/edit'),
        ];
    }
}
