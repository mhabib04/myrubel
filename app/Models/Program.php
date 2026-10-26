<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasUnit;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasImages, HasSlug, HasUnit, Publishable;

    /** Label isian yang berbeda per website, dipakai di admin dan halaman detail. */
    public const LABELS = [
        'bimbel' => ['subjects' => 'Mata pelajaran', 'features' => 'Fasilitas unggulan', 'price_unit' => 'semester'],
        'literacy' => ['subjects' => 'Materi', 'features' => 'Keunggulan program', 'price_unit' => 'bulan'],
        'motivation' => ['subjects' => 'Materi kegiatan', 'features' => 'Keunggulan program', 'price_unit' => 'peserta'],
    ];

    protected string $slugSource = 'name';

    protected array $imageFields = ['image'];

    protected $table = 'programs';

    protected $fillable = ['name', 'slug', 'short_desc', 'image', 'schedule', 'subjects', 'price', 'price_unit', 'features', 'is_published', 'sort_order'];

    protected $casts = ['features' => 'array', 'price' => 'integer'];
}
