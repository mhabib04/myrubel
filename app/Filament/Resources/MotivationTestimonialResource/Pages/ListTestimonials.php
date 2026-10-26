<?php

namespace App\Filament\Resources\MotivationTestimonialResource\Pages;

use App\Filament\Resources\MotivationTestimonialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTestimonials extends ListRecords
{
    protected static string $resource = MotivationTestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
