<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasUnit;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasImages, HasUnit, Publishable;

    protected array $imageFields = ['photo'];

    protected ?string $dateColumn = 'year';

    protected $table = 'testimonials';

    protected $fillable = ['photo', 'name', 'year', 'graduated_to', 'quote', 'is_published', 'sort_order'];
}
