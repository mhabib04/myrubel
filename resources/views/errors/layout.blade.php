{{-- Halaman error semua website. Tanpa query database, supaya tetap tampil saat database bermasalah. --}}
@php
  $unit = \App\Support\Site::fromHost(request()->getHost());
  $site = \App\Support\Site::UNITS[$unit];
@endphp
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">
  <title>@yield('title') – {{ $site['name'] }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css'])
</head>
<body class="error-page">
  <main class="error-box">
    <p class="error-code">@yield('code')</p>
    <h1>@yield('title')</h1>
    <p class="error-text">@yield('message')</p>
    <a class="btn btn-navy" href="{{ \App\Support\Site::homeUrl($unit) }}">
      Kembali ke {{ $site['name'] }}
      <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </a>
  </main>
</body>
</html>
