{{-- Satu jenis karya di beranda: 6 terbaru + tombol ke halaman daftar. $type, $items, $bg --}}
@use('App\Models\Work')
@use('App\Support\Site')
<section @if ($bg) class="{{ $bg }}" @endif id="{{ $type }}" data-nav="karya">
  <div class="container">
    @include('partials.sec-title', ['title' => Work::label($type), 'sub' => Work::TYPES[$type][1]])
    <div class="info-list">
      @foreach ($items as $work)
      @include('literacy.work-item', ['work' => $work])
      @endforeach
    </div>
    <div class="more">
      <a class="btn btn-navy" href="{{ Site::route('karya.index', $type) }}">Lihat yang lainnya<svg class="icon"><use href="#i-arrow"/></svg></a>
    </div>
  </div>
</section>
