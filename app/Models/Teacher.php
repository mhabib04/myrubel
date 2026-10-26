<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasImages, Publishable;

    protected array $imageFields = ['photo'];

    protected $table = 'teachers';

    protected $fillable = ['photo', 'name', 'subject', 'almamater', 'motto', 'is_published', 'sort_order'];
}
