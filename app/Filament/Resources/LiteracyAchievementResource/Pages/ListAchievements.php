<?php

namespace App\Filament\Resources\LiteracyAchievementResource\Pages;

use App\Filament\Resources\LiteracyAchievementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAchievements extends ListRecords
{
    protected static string $resource = LiteracyAchievementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
