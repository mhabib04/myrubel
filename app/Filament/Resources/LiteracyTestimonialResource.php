<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiteracyTestimonialResource\Pages;
use UnitEnum;

/** Testimonial milik website Literacy Center: form & tabel sama, data terpisah. */
class LiteracyTestimonialResource extends TestimonialResource
{
    protected static string $unit = 'literacy';

    protected static ?string $slug = 'literacy/testimoni';

    protected static ?int $navigationSort = 5;

    protected static string|UnitEnum|null $navigationGroup = 'Literacy Center';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
