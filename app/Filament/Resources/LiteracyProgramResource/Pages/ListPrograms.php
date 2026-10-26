<?php

namespace App\Filament\Resources\LiteracyProgramResource\Pages;

use App\Filament\Resources\LiteracyProgramResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPrograms extends ListRecords
{
    protected static string $resource = LiteracyProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
