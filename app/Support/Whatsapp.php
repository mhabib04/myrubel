<?php

namespace App\Support;

class Whatsapp
{
    /** Ubah "0812-3456-7890" / "+62 812..." menjadi "6281234567890" untuk wa.me. */
    public static function normalize(?string $number): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $number);

        if ($digits === '') {
            return null;
        }

        return str_starts_with($digits, '0') ? '62'.substr($digits, 1) : $digits;
    }

    public static function link(?string $number, string $message): string
    {
        return 'https://wa.me/'.static::normalize($number).'?text='.rawurlencode($message);
    }
}
