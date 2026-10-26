<?php

namespace App\Filament\Support;

use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Builder;

/**
 * Resource yang datanya milik satu website (unit). Resource memakai trait ini dan
 * mendeklarasikan `protected static string $unit`; turunan Literacy cukup mengganti nilainya.
 */
trait ScopedToUnit
{
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->unit(static::$unit);
    }

    /** Disisipkan di form agar data baru tersimpan sebagai milik unit ini. */
    protected static function unitField(): Hidden
    {
        return Hidden::make('unit')->default(static::$unit);
    }
}
