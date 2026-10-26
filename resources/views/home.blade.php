@extends('layouts.app', ['navActive' => 'beranda'])

@section('title', 'Bimbel My Rubel – Rumah Belajar Pertama di Pasaman Barat')
@section('description', 'Bimbel My Rubel Pasaman Barat. Bimbingan belajar TK–SMA, olimpiade & Basic English sejak 2016.')

@section('content')
@include('home.hero')
@include('home.kenapa')
@isset($sections['program']) @include('home.program') @endisset
@isset($sections['prestasi'])
  @include('partials.carousel', ['id' => 'prestasi', 'title' => 'Prestasi', 'bg' => $sections['prestasi'],
    'sub' => 'Deretan prestasi siswa My Rubel di berbagai ajang.',
    'slides' => $achievements->map(fn ($a, $i) => [
      'src' => $a->imageUrl('poster'), 'alt' => $a->title, 'ph' => 'POSTER PRESTASI '.($i + 1),
      'tag' => $a->level === 'Lainnya' ? $a->level : 'Tingkat '.$a->level,
      'title' => $a->title, 'small' => ($a->student_name ?: '[Nama siswa]').' · '.($a->year ?: '[Tahun]'),
    ])])
@endisset
@isset($sections['alumni']) @include('home.alumni') @endisset
@isset($sections['testimoni']) @include('home.testimoni') @endisset
@isset($sections['event']) @include('home.event') @endisset
@isset($sections['info']) @include('home.info') @endisset
@isset($sections['galeri']) @include('home.galeri') @endisset
@include('home.tentang')
@isset($sections['pengajar']) @include('home.pengajar') @endisset
@isset($sections['lokasi']) @include('home.lokasi') @endisset
@include('home.ikuti')
@endsection
