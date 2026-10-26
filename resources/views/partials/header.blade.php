@use('App\Support\Site')
@php
  // di beranda pakai anchor (#), di halaman lain kembali ke beranda website ini
  $base = Site::isHome() ? '' : Site::route('home');
  $active = $navActive ?? 'beranda';
  [$brandTop, $brandBottom] = Site::brand();
@endphp
<header class="header">
  <div class="container">
    <a href="{{ Site::route('home') }}" class="wordmark">@if ($logo = $setting->imageUrl('logo'))<img src="{{ $logo }}" alt="Logo My Rubel">@else<span class="mark">MR</span>@endif<span>{{ $brandTop }}<small>{{ $brandBottom }}</small></span></a>
    <nav class="nav-links" id="nav-links">
      @foreach ($menu as $id => $label)
        @if ($id === 'karya')
        {{-- dropdown: tiap jenis karya langsung ke halaman daftarnya --}}
        <div class="nav-drop">
          <button type="button" class="nav-drop-btn{{ $active === 'karya' ? ' active' : '' }}" aria-expanded="false">{{ $label }}<svg class="icon"><use href="#i-down"/></svg></button>
          <div class="nav-drop-menu">
            @foreach (Site::workTypes($sections) as $type => $typeLabel)
            <a href="{{ Site::route('karya.index', $type) }}"@if (($workType ?? null) === $type) class="current"@endif>{{ $typeLabel }}</a>
            @endforeach
          </div>
        </div>
        @else
        <a href="{{ $base }}#{{ $id }}"@if ($id === $active) class="active"@endif>{{ $label }}</a>
        @endif
      @endforeach
    </nav>
    <button class="burger" aria-label="Buka menu" aria-expanded="false" aria-controls="nav-links"><svg class="icon"><use href="#i-menu"/></svg></button>
  </div>
</header>
