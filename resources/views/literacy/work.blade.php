{{-- Detail satu karya: jenis, judul, poster, penulis, tanggal, isi, profil penulis, tombol bagikan. $work --}}
@use('App\Support\Site')
@extends('layouts.app', ['navActive' => 'karya', 'workType' => $work->type, 'shareImage' => $work->imageUrl('poster') ?: $logo])

@php
  // judul untuk tab, preview link (WhatsApp, dll.), dan tombol Bagikan: "Judul oleh Penulis"
  $shareTitle = $work->author ? $work->title.' oleh '.$work->author : $work->title;
@endphp
@section('title', $shareTitle.' – My Rubel Literacy Center Pasaman Barat')
@section('description', $work->excerpt(30) ?: $work->typeLabel().' karya siswa My Rubel Literacy Center.')

@section('content')
<section>
  <div class="container">
    <a class="back-link" data-back href="{{ Site::route('karya.index', $work->type) }}"><svg class="icon"><use href="#i-left"/></svg>Kembali ke {{ $work->typeLabel() }}</a>
    {{-- jenis, judul, poster landscape, penulis & tanggal, isi; lalu kartu profil penulis + tombol bagikan --}}
    <article class="detail work-detail">
      <header class="work-head">
        <span class="cap-tag work-type">{{ $work->typeLabel() }}</span>
        <h1>{{ $work->title }}</h1>
      </header>
      <div class="work-poster">
        @include('partials.img', ['src' => $work->imageUrl('poster'), 'alt' => $work->title, 'ph' => 'POSTER KARYA', 'eager' => true])
      </div>
      <div class="detail-meta">
        @if ($work->author)
        <span class="tag"><svg class="icon"><use href="#i-pen"/></svg>Karya oleh {{ $work->author }}</span>
        @endif
        <span class="tag"><svg class="icon"><use href="#i-calendar"/></svg>{{ Site::tanggal($work->date) }}</span>
      </div>
      @if (filled($work->content))
      <div class="prose">{{ \Filament\Forms\Components\RichEditor\RichContentRenderer::make($work->content) }}</div>
      @endif
      <footer class="work-foot">
        @if ($work->author)
        <div class="author-card">
          <div class="author-photo">@include('partials.img', ['src' => $work->imageUrl('author_photo'), 'alt' => $work->author, 'ph' => 'FOTO'])</div>
          <div class="author-info">
            <span class="author-label">Penulis</span>
            <b>{{ $work->author }}</b>
            @if ($birth = $work->authorBirth())
            <span>{{ $birth }}</span>
            @endif
            @if ($work->author_school)
            <span>{{ $work->author_school }}</span>
            @endif
          </div>
        </div>
        @endif
        @include('partials.share', ['shareTitle' => $shareTitle])
      </footer>
    </article>
  </div>
</section>
@endsection
