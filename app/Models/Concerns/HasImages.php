<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Storage;

/**
 * Menghapus file gambar lama di disk public saat gambar diganti atau datanya dihapus.
 * Model mendaftarkan kolom gambarnya di properti $imageFields.
 */
trait HasImages
{
    public static function bootHasImages(): void
    {
        static::updated(function ($model) {
            foreach ($model->imageFields as $field) {
                $old = $model->getOriginal($field);
                if ($model->wasChanged($field) && $old) {
                    Storage::disk('public')->delete($old);
                }
            }
        });

        static::deleted(function ($model) {
            foreach ($model->imageFields as $field) {
                if ($model->{$field}) {
                    Storage::disk('public')->delete($model->{$field});
                }
            }
        });
    }

    /** URL publik gambar, atau null jika belum diisi (tampilkan placeholder). */
    public function imageUrl(string $field): ?string
    {
        return $this->{$field} ? Storage::disk('public')->url($this->{$field}) : null;
    }
}
