<?php

namespace App\Filament\Resources\MotivationActivityResource\Pages;

use App\Filament\Resources\MotivationActivityResource;
use Filament\Resources\Pages\CreateRecord;

class CreateActivity extends CreateRecord
{
    protected static string $resource = MotivationActivityResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
