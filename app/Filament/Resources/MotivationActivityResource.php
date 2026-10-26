<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MotivationActivityResource\Pages;
use UnitEnum;

/** Activity milik website Motivation & Outbound Center: form & tabel sama, data terpisah. */
class MotivationActivityResource extends ActivityResource
{
    protected static string $unit = 'motivation';

    protected static ?string $slug = 'motivation/event';

    protected static ?int $navigationSort = 2;

    protected static string|UnitEnum|null $navigationGroup = 'Motivation & Outbound';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'create' => Pages\CreateActivity::route('/create'),
            'edit' => Pages\EditActivity::route('/{record}/edit'),
        ];
    }
}
