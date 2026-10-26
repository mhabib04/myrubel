<?php

namespace App\Filament\Resources\AlumnusResource\Pages;

use App\Filament\Resources\AlumnusResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAlumnus extends CreateRecord
{
    protected static string $resource = AlumnusResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
