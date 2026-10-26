<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiteracyProgramResource\Pages;
use UnitEnum;

/** Program milik website Literacy Center: form & tabel sama, data terpisah. */
class LiteracyProgramResource extends ProgramResource
{
    protected static string $unit = 'literacy';

    protected static ?string $slug = 'literacy/program';

    protected static ?int $navigationSort = 1;

    protected static string|UnitEnum|null $navigationGroup = 'Literacy Center';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'edit' => Pages\EditProgram::route('/{record}/edit'),
        ];
    }
}
