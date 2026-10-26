<?php

namespace App\Filament\Resources\MotivationTestimonialResource\Pages;

use App\Filament\Resources\MotivationTestimonialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTestimonial extends CreateRecord
{
    protected static string $resource = MotivationTestimonialResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
