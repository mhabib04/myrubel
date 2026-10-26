<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasImages, Publishable;

    protected array $imageFields = ['poster'];

    protected ?string $dateColumn = 'year';

    protected $table = 'achievements';

    protected $fillable = ['poster', 'title', 'level', 'student_name', 'year', 'is_published', 'sort_order'];

    public const LEVELS = ['Kabupaten', 'Provinsi', 'Nasional', 'Lainnya'];
}
