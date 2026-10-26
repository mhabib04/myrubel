<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MotivationProgramResource\Pages;
use UnitEnum;

/** Program milik website Motivation & Outbound Center: form & tabel sama, data terpisah. */
class MotivationProgramResource extends ProgramResource
{
    protected static string $unit = 'motivation';

    protected static ?string $slug = 'motivation/program';

    protected static ?int $navigationSort = 1;

    protected static string|UnitEnum|null $navigationGroup = 'Motivation & Outbound';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'edit' => Pages\EditProgram::route('/{record}/edit'),
        ];
    }
}
