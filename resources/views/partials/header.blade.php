@php
  // di beranda pakai anchor (#), di halaman lain kembali ke beranda
  $base = request()->routeIs('home') ? '' : route('home');
  $active = $navActive ?? 'beranda';
@endphp
<header class="header">
  <div class="container">
    <a href="{{ route('home') }}" class="wordmark">@if ($logo = $setting->imageUrl('logo'))<img src="{{ $logo }}" alt="Logo My Rubel">@else<span class="mark">MR</span>@endif<span>Bimbel<small>My Rubel</small></span></a>
    <nav class="nav-links" id="nav-links">
      @foreach (\App\Support\Site::menu($sections) as $id => $label)
      <a href="{{ $base }}#{{ $id }}"@if ($id === $active) class="active"@endif>{{ $label }}</a>
      @endforeach
    </nav>
    <button class="burger" aria-label="Buka menu" aria-expanded="false" aria-controls="nav-links"><svg class="icon"><use href="#i-menu"/></svg></button>
  </div>
</header>
