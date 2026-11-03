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

    protected array $imageFields = ['poster', 'author_photo'];

    protected ?string $dateColumn = 'date';

    protected $table = 'works';

    protected $fillable = ['type', 'title', 'slug', 'author', 'author_photo', 'author_birthplace', 'author_birthdate', 'author_school', 'date', 'poster', 'content', 'is_published', 'sort_order'];

    protected $casts = ['date' => 'date', 'author_birthdate' => 'date'];

    public static function label(string $type): string
    {
        return static::TYPES[$type][0] ?? ucfirst($type);
    }

    /** "Simpang Empat, 12 Mei 2010"; bagian yang kosong dilewati. */
    public function authorBirth(): ?string
    {
        $date = $this->author_birthdate ? \App\Support\Site::tanggal($this->author_birthdate) : null;

        return implode(', ', array_filter([$this->author_birthplace, $date])) ?: null;
    }

    public function typeLabel(): string
    {
        return static::label($this->type);
    }
}
