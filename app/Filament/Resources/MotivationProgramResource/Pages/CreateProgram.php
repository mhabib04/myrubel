<?php

namespace App\Filament\Resources\MotivationProgramResource\Pages;

use App\Filament\Resources\MotivationProgramResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProgram extends CreateRecord
{
    protected static string $resource = MotivationProgramResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
