<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasImages, Publishable;

    protected array $imageFields = ['image'];

    protected $table = 'galleries';

    protected $fillable = ['image', 'caption', 'is_published', 'sort_order'];
}
