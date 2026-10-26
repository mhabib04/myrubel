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
        'logo', 'hero_title', 'hero_subtitle', 'hero_tagline', 'hero_image',
        'whatsapp', 'whatsapp_name', 'tiktok_url', 'instagram_url',
        'about_title', 'about_body', 'about_image', 'quote', 'quote_author',
    ];

    public static function current(): self
    {
        return static::query()->firstOrCreate([], ['hero_title' => 'Bimbel My Rubel']);
    }

    protected function whatsapp(): Attribute
    {
        return Attribute::set(fn (?string $value) => Whatsapp::normalize($value));
    }
}
