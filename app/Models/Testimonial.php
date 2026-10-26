<?php

namespace App\Models;

use App\Models\Concerns\HasImages;
use App\Models\Concerns\HasUnit;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasImages, HasUnit, Publishable;

    /** Label isian yang berbeda per website, dipakai di admin dan beranda. */
    public const LABELS = [
        'bimbel' => ['year' => 'Tahun lulus', 'graduated_to' => 'Lulus di', 'graduated_to_help' => 'Kampus atau sekolah tujuan setelah lulus.', 'tag' => 'Alumni'],
        'literacy' => ['year' => 'Tahun', 'graduated_to' => 'Keterangan', 'graduated_to_help' => 'Mis. asal sekolah atau kelas.', 'tag' => 'Peserta'],
        'motivation' => ['year' => 'Tahun', 'graduated_to' => 'Keterangan', 'graduated_to_help' => 'Mis. asal sekolah, instansi, atau kegiatan yang diikuti.', 'tag' => 'Peserta'],
    ];

    protected array $imageFields = ['photo'];

    protected ?string $dateColumn = 'year';

    protected $table = 'testimonials';

    protected $fillable = ['photo', 'name', 'year', 'graduated_to', 'quote', 'is_published', 'sort_order'];
}
