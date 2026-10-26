<?php

namespace App\Filament\Resources\LiteracyTestimonialResource\Pages;

use App\Filament\Resources\LiteracyTestimonialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTestimonials extends ListRecords
{
    protected static string $resource = LiteracyTestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
