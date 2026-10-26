<?php

namespace App\Filament\Resources\LiteracyProgramResource\Pages;

use App\Filament\Resources\LiteracyProgramResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProgram extends CreateRecord
{
    protected static string $resource = LiteracyProgramResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
