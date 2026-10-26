<?php

namespace App\Filament\Resources\LiteracyTestimonialResource\Pages;

use App\Filament\Resources\LiteracyTestimonialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTestimonial extends CreateRecord
{
    protected static string $resource = LiteracyTestimonialResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
