<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasImages, HasSlug, Publishable;

    protected string $slugSource = 'name';

    protected array $imageFields = ['image'];

    protected $table = 'programs';

    protected $fillable = ['name', 'slug', 'short_desc', 'image', 'schedule', 'subjects', 'price', 'price_unit', 'features', 'is_published', 'sort_order'];

    protected $casts = ['features' => 'array', 'price' => 'integer'];
}
