@extends('layouts.app', ['navActive' => 'program', 'shareImage' => $program->imageUrl('image') ?: $logo])

@section('title', $program->name.' – '.\App\Support\Site::name().' Pasaman Barat')
@section('description', 'Program belajar dan fasilitas unggulan '.\App\Support\Site::name().' Pasaman Barat.')
@section('wa_message', 'Halo My Rubel, saya mau tanya program '.$program->name.'.')

@php
  $items = array_filter([
      $program->schedule ? e($program->schedule) : null,
      $program->subjects ? e(\App\Models\Program::LABELS[\App\Support\Site::unit()]['subjects']).': <small>'.e($program->subjects).'</small>' : null,
      ...array_map('e', $program->features ?? []),
  ]);
@endphp

@section('content')
<section>
  <div class="container">
    <a class="back-link" data-back href="{{ \App\Support\Site::route('home') }}#program"><svg class="icon"><use href="#i-left"/></svg>Kembali ke Program</a>
    <div class="detail">
      <div>
        <h1 id="p-title">{{ $program->name }}</h1>
        <h2>Program Belajar &amp; Fasilitas Unggulan</h2>
        <ul class="checks" id="p-checks">
          @foreach ($items as $item)
          <li><span class="tick"><svg class="icon"><use href="#i-check"/></svg></span><span>{!! $item !!}</span></li>
          @endforeach
        </ul>
        <div class="info-daftar">
          <b>Pendaftaran &amp; Informasi</b>
          <p>Silakan chat dengan admin kami untuk informasi lebih lengkap.</p>
          <div class="detail-actions">
            <a class="btn btn-wa" id="p-wa" data-wa-pick data-wa="Halo My Rubel, saya mau tanya program {{ $program->name }}."><svg class="icon"><use href="#i-chat"/></svg>Konsultasi Gratis (WA)</a>
            @include('partials.share', ['shareTitle' => $program->name])
          </div>
        </div>
      </div>
      <div class="detail-side">
        @include('partials.img', ['src' => $program->imageUrl('image'), 'alt' => $program->name, 'ph' => 'GAMBAR '.mb_strtoupper($program->name), 'eager' => true])
        <div class="price" id="p-price">{{ $program->price ? 'Rp'.number_format($program->price, 0, ',', '.') : '[Harga]' }}<small>/{{ $program->price_unit }}</small></div>
      </div>
    </div>
  </div>
</section>
@endsection
