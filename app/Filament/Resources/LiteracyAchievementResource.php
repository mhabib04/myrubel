<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiteracyAchievementResource\Pages;
use UnitEnum;

/** Achievement milik website Literacy Center: form & tabel sama, data terpisah. */
class LiteracyAchievementResource extends AchievementResource
{
    protected static string $unit = 'literacy';

    protected static ?string $slug = 'literacy/prestasi';

    protected static ?int $navigationSort = 2;

    protected static string|UnitEnum|null $navigationGroup = 'Literacy Center';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievement::route('/create'),
            'edit' => Pages\EditAchievement::route('/{record}/edit'),
        ];
    }
}
