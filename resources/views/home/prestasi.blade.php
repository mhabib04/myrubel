@include('partials.carousel', ['id' => 'prestasi', 'title' => 'Prestasi', 'bg' => $sections['prestasi'],
  'sub' => 'Deretan prestasi siswa My Rubel di berbagai ajang.',
  'slides' => $achievements->map(fn ($a, $i) => [
    'src' => $a->imageUrl('poster'), 'alt' => $a->title, 'ph' => 'POSTER PRESTASI '.($i + 1),
    'tag' => $a->level === 'Lainnya' ? $a->level : 'Tingkat '.$a->level,
    'title' => $a->title, 'small' => ($a->student_name ?: '[Nama siswa]').' · '.($a->year ?: '[Tahun]'),
  ])])
