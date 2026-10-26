<?php

namespace App\Models;

use App\Models\Concerns\HasExcerpt;
use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasExcerpt, HasImages, HasSlug, Publishable;

    protected array $imageFields = ['poster'];

    protected ?string $dateColumn = 'date';

    protected $table = 'infos';

    protected $fillable = ['poster', 'title', 'slug', 'content', 'date', 'is_published', 'sort_order'];

    protected $casts = ['date' => 'date'];
}
