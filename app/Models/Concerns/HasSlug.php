<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Slug (alamat halaman detail) dibuat otomatis dari judul (atau $slugSource) saat pertama
 * disimpan, dan selalu unik. Slug yang sudah ada tidak berubah agar link lama tetap jalan.
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            $source = property_exists($model, 'slugSource') ? $model->slugSource : 'title';
            $slug = Str::slug($model->slug ?: $model->{$source}) ?: 'data';
            $base = $slug;
            for ($i = 2; static::query()->where('slug', $slug)->whereKeyNot($model->getKey())->exists(); $i++) {
                $slug = $base.'-'.$i;
            }
            $model->slug = $slug;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
