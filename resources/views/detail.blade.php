{{-- Halaman "Lihat Selengkapnya" untuk Event & Info: $item, $navActive, $image, $meta --}}
@extends('layouts.app', ['navActive' => $navActive])

@php
  $label = $navActive === 'event' ? 'Event' : 'Info';
@endphp

@section('title', $item->title.' – '.\App\Support\Site::name().' Pasaman Barat')
@section('description', $item->excerpt(30) ?: 'Event dan informasi terbaru dari '.\App\Support\Site::name().' Pasaman Barat.')

@section('content')
<section>
  <div class="container">
    <a class="back-link" data-back href="{{ \App\Support\Site::route('home') }}#{{ $navActive }}"><svg class="icon"><use href="#i-left"/></svg>Kembali ke {{ $label }}</a>
    {{-- foto mengapung di kanan, isi lengkap mengalir di bawahnya --}}
    <article class="detail detail-article">
      <div class="article-img">
        @include('partials.img', ['src' => $image, 'alt' => $item->title, 'ph' => $navActive === 'event' ? 'FOTO EVENT' : 'POSTER INFO', 'eager' => true])
      </div>
      <h1>{{ $item->title }}</h1>
      @if ($meta)
      <div class="detail-meta">
        @foreach ($meta as $i => $m)
        <span class="tag"><svg class="icon"><use href="#{{ $i === 0 ? 'i-calendar' : 'i-pin' }}"/></svg>{{ $m }}</span>
        @endforeach
      </div>
      @endif
      @if (filled($item->content))
      <div class="prose">{{ \Filament\Forms\Components\RichEditor\RichContentRenderer::make($item->content) }}</div>
      @endif
      <div class="info-daftar">
        <b>Ingin tahu lebih lanjut?</b>
        <p>Silakan chat dengan admin kami.</p>
        <div class="detail-actions">
          <a class="btn btn-wa" data-wa-pick data-wa="Halo My Rubel, saya mau tanya tentang {{ $item->title }}."><svg class="icon"><use href="#i-chat"/></svg>Tanya via WhatsApp</a>
          @include('partials.share', ['shareTitle' => $item->title])
        </div>
      </div>
    </article>
  </div>
</section>
@endsection

@section('wa_message', 'Halo My Rubel, saya mau tanya tentang '.$item->title.'.')
