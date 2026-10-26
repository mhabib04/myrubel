<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MotivationTestimonialResource\Pages;
use UnitEnum;

/** Testimonial milik website Motivation & Outbound Center: form & tabel sama, data terpisah. */
class MotivationTestimonialResource extends TestimonialResource
{
    protected static string $unit = 'motivation';

    protected static ?string $slug = 'motivation/testimoni';

    protected static ?int $navigationSort = 3;

    protected static string|UnitEnum|null $navigationGroup = 'Motivation & Outbound';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
