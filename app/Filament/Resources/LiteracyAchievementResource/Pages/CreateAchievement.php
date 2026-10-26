<?php

namespace App\Filament\Resources\LiteracyAchievementResource\Pages;

use App\Filament\Resources\LiteracyAchievementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAchievement extends CreateRecord
{
    protected static string $resource = LiteracyAchievementResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
