<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use App\Support\Whatsapp;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use Publishable;

    public const TYPES = ['Pusat', 'Cabang'];

    /** Hanya alamat peta tempel resmi Google yang diterima dari kode "Sematkan peta". */
    public const EMBED_PATTERN = '~https://www\.google\.com/maps/embed\?pb=[^"\'\s<>]+~';

    protected $table = 'branches';

    protected $fillable = ['name', 'type', 'address', 'maps_query', 'whatsapp', 'is_coming_soon', 'is_published', 'sort_order'];

    protected $casts = ['is_coming_soon' => 'boolean'];

    protected function whatsapp(): Attribute
    {
        return Attribute::set(fn (?string $value) => Whatsapp::normalize($value));
    }

    /** Kode <iframe> yang ditempel admin disimpan sebagai alamat petanya saja. */
    protected function mapsQuery(): Attribute
    {
        return Attribute::set(function (?string $value) {
            $value = trim((string) $value);

            return static::extractEmbedUrl($value) ?? ($value === '' ? null : $value);
        });
    }

    public static function extractEmbedUrl(?string $value): ?string
    {
        return preg_match(static::EMBED_PATTERN, html_entity_decode((string) $value), $m) ? $m[0] : null;
    }

    /**
     * Alamat peta untuk iframe: kode "Sematkan peta" Google jika ada,
     * selain itu pencarian teks/koordinat (atau nama cabang jika kosong).
     */
    public function mapEmbedUrl(): string
    {
        if ($embed = static::extractEmbedUrl($this->maps_query)) {
            return $embed;
        }

        $query = $this->maps_query ?: $this->name.', Pasaman Barat';

        return 'https://maps.google.com/maps?q='.rawurlencode($query).'&z=14&output=embed';
    }
}
