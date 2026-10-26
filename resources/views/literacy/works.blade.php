{{-- Halaman daftar satu jenis karya, 20 per halaman. $type, $works (paginator) --}}
@use('App\Models\Work')
@use('App\Support\Site')
@extends('layouts.app', ['navActive' => 'karya', 'workType' => $type])

@section('title', Work::label($type).' – My Rubel Literacy Center Pasaman Barat')
@section('description', Work::TYPES[$type][1])

@section('content')
<section>
  <div class="container">
    <a class="back-link" data-back href="{{ Site::route('home') }}#{{ $type }}"><svg class="icon"><use href="#i-left"/></svg>Kembali ke Beranda</a>
    @include('partials.sec-title', ['title' => Work::label($type), 'sub' => Work::TYPES[$type][1]])
    @if ($works->isEmpty())
    <p class="empty-note">Belum ada {{ strtolower(Work::label($type)) }} yang dipublikasikan.</p>
    @else
    <div class="info-list">
      @foreach ($works as $work)
      @include('literacy.work-item', ['work' => $work])
      @endforeach
    </div>
    {{ $works->links('partials.pagination') }}
    @endif
  </div>
</section>
@endsection
