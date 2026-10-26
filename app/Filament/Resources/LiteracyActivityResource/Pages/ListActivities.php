<?php

namespace App\Filament\Resources\LiteracyActivityResource\Pages;

use App\Filament\Resources\LiteracyActivityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    protected static string $resource = LiteracyActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
