<?php

namespace App\Filament\Resources\MotivationActivityResource\Pages;

use App\Filament\Resources\MotivationActivityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    protected static string $resource = MotivationActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
