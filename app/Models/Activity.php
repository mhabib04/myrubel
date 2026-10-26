<?php

namespace App\Models;

use App\Models\Concerns\HasExcerpt;
use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasExcerpt, HasImages, HasSlug, Publishable;

    protected array $imageFields = ['photo'];

    protected ?string $dateColumn = 'date';

    protected $table = 'activities';

    protected $fillable = ['photo', 'title', 'slug', 'content', 'date', 'place', 'is_published', 'sort_order'];

    protected $casts = ['date' => 'date'];
}
