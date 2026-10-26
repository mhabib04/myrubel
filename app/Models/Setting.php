<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Support\Whatsapp;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * Pengaturan situs: hanya satu baris, diambil lewat Setting::current().
 */
class Setting extends Model
{
    use HasImages;

    protected array $imageFields = ['logo', 'hero_image', 'about_image'];

    protected $fillable = [
        'unit', 'logo', 'hero_title', 'hero_subtitle', 'hero_tagline', 'hero_image',
        'whatsapp', 'whatsapp_name', 'tiktok_url', 'instagram_url',
        'about_title', 'about_body', 'about_image', 'quote', 'quote_author',
    ];

    /** Pengaturan utama My Rubel: logo, WhatsApp, dan teks website Bimbel. */
    public static function current(): self
    {
        return static::for('bimbel');
    }

    /** Pengaturan per website (unit); logo & WhatsApp tetap diambil dari current(). */
    public static function for(string $unit): self
    {
        return static::query()->firstOrCreate(['unit' => $unit], ['hero_title' => match ($unit) {
            'literacy' => 'My Rubel *Literacy* Center',
            'motivation' => 'My Rubel *Motivation* & Outbound Center',
            default => 'Bimbel My Rubel',
        }]);
    }

    protected function whatsapp(): Attribute
    {
        return Attribute::set(fn (?string $value) => Whatsapp::normalize($value));
    }
}
