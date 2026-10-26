<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Kolom is_published (tampil/sembunyi) dan sort_order (urutan) yang dipakai semua koleksi.
 *
 * Koleksi yang punya tanggal/tahun mengisi $dateColumn: datanya diurutkan dari yang terbaru,
 * bukan dari sort_order.
 */
trait Publishable
{
    public static function bootPublishable(): void
    {
        // data baru otomatis ditaruh di urutan paling akhir
        static::creating(function ($model) {
            if (! $model->sort_order) {
                $model->sort_order = (int) static::query()->max('sort_order') + 1;
            }
        });
    }

    public function initializePublishable(): void
    {
        $this->mergeCasts(['is_published' => 'boolean', 'sort_order' => 'integer']);
    }

    public function dateColumn(): ?string
    {
        return property_exists($this, 'dateColumn') ? $this->dateColumn : null;
    }

    /** Hanya data yang dipublikasikan, dengan urutan tampil di website. */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)->ordered();
    }

    /** Terbaru dulu untuk koleksi bertanggal (yang kosong di akhir), selain itu sesuai sort_order. */
    public function scopeOrdered(Builder $query): Builder
    {
        if ($column = $this->dateColumn()) {
            return $query->orderByRaw("{$column} is null")->orderByDesc($column)->orderByDesc('id');
        }

        return $query->orderBy('sort_order')->orderBy('id');
    }
}
