<?php

namespace App\Models;

use App\Models\Concerns\HasExcerpt;
use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

/** Karya Literacy Center. */
class Work extends Model
{
    use HasExcerpt, HasImages, HasSlug, Publishable;

    /** jenis karya => [label, sub judul section] */
    public const TYPES = [
        'puisi' => ['Puisi', 'Puisi karya siswa My Rubel Literacy Center.'],
        'cerpen' => ['Cerpen', 'Cerita pendek karya siswa My Rubel Literacy Center.'],
        'essay' => ['Essay', 'Gagasan dan pemikiran siswa dalam bentuk essay.'],
        'artikel' => ['Artikel', 'Artikel tulisan siswa My Rubel Literacy Center.'],
    ];

    protected array $imageFields = ['poster'];

    protected ?string $dateColumn = 'date';

    protected $table = 'works';

    protected $fillable = ['type', 'title', 'slug', 'author', 'date', 'poster', 'content', 'is_published', 'sort_order'];

    protected $casts = ['date' => 'date'];

    public static function label(string $type): string
    {
        return static::TYPES[$type][0] ?? ucfirst($type);
    }

    public function typeLabel(): string
    {
        return static::label($this->type);
    }
}
