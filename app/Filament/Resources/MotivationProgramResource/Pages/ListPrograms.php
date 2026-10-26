<?php

namespace App\Filament\Resources\MotivationProgramResource\Pages;

use App\Filament\Resources\MotivationProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrograms extends ListRecords
{
    protected static string $resource = MotivationProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
