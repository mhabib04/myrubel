<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiteracyActivityResource\Pages;
use UnitEnum;

/** Activity milik website Literacy Center: form & tabel sama, data terpisah. */
class LiteracyActivityResource extends ActivityResource
{
    protected static string $unit = 'literacy';

    protected static ?string $slug = 'literacy/event';

    protected static ?int $navigationSort = 4;

    protected static string|UnitEnum|null $navigationGroup = 'Literacy Center';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}
