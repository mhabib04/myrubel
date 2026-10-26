<?php

namespace App\Filament\Resources\LiteracyActivityResource\Pages;

use App\Filament\Resources\LiteracyActivityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateActivity extends CreateRecord
{
    protected static string $resource = LiteracyActivityResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
