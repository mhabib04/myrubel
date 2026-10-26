<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Alumnus extends Model
{
    use HasImages, Publishable;

    protected array $imageFields = ['photo'];

    protected ?string $dateColumn = 'graduation_year';

    protected $table = 'alumni';

    protected $fillable = ['photo', 'name', 'graduation_year', 'campus', 'major', 'is_published', 'sort_order'];
}
