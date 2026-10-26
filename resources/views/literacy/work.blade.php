{{-- Detail satu karya: jenis, poster, penulis, tanggal, isi, tombol bagikan. $work --}}
@use('App\Support\Site')
@extends('layouts.app', ['navActive' => 'karya', 'workType' => $work->type])

@section('title', $work->title.' – My Rubel Literacy Center Pasaman Barat')
@section('description', $work->excerpt(30) ?: $work->typeLabel().' karya siswa My Rubel Literacy Center.')

@section('content')
<section>
  <div class="container">
    <a class="back-link" data-back href="{{ Site::route('karya.index', $work->type) }}"><svg class="icon"><use href="#i-left"/></svg>Kembali ke {{ $work->typeLabel() }}</a>
    {{-- poster mengapung di kanan, isi karya mengalir di bawahnya --}}
    <article class="detail detail-article">
      <div class="article-img">
        @include('partials.img', ['src' => $work->imageUrl('poster'), 'alt' => $work->title, 'ph' => 'POSTER KARYA', 'eager' => true])
      </div>
      <span class="cap-tag work-type">{{ $work->typeLabel() }}</span>
      <h1>{{ $work->title }}</h1>
      <div class="detail-meta">
        @if ($work->author)
        <span class="tag"><svg class="icon"><use href="#i-pen"/></svg>Karya oleh {{ $work->author }}</span>
        @endif
        <span class="tag"><svg class="icon"><use href="#i-calendar"/></svg>{{ Site::tanggal($work->date) }}</span>
      </div>
      @if (filled($work->content))
      <div class="prose">{{ \Filament\Forms\Components\RichEditor\RichContentRenderer::make($work->content) }}</div>
      @endif
      <div class="detail-actions work-actions">
        @include('partials.share', ['shareTitle' => $work->title])
      </div>
    </article>
  </div>
</section>
@endsection
