<section @if ($sections['lokasi']) class="{{ $sections['lokasi'] }}" @endif id="lokasi">
  <div class="container">
    @include('partials.sec-title', ['title' => 'Lokasi', 'sub' => 'Kunjungi cabang My Rubel terdekat.'])

    @foreach ($branches as $b)
    @if ($b->is_coming_soon)
    <div class="loc-row soon">
      <div class="branch">
        <span class="tag">{{ $b->type }}</span>
        <h3>{{ $b->name }}</h3>
      </div>
      <span class="tag soon"><svg class="icon"><use href="#i-clock"/></svg>Segera hadir</span>
    </div>
    @else
    <div class="loc-row">
      <div class="branch">
        <span class="tag">{{ $b->type }}</span>
        <h3>{{ $b->name }}</h3>
        @if ($b->address)
        <p><svg class="icon"><use href="#i-pin"/></svg>{{ $b->address }}</p>
        @endif
        <a class="btn btn-wa btn-sm" data-wa="Halo My Rubel {{ $b->name }}, saya mau tanya info bimbel."@if ($b->whatsapp) data-wa-number="{{ $b->whatsapp }}"@endif><svg class="icon"><use href="#i-chat"/></svg>WhatsApp</a>
      </div>
      <div class="map"><iframe loading="lazy" title="Peta My Rubel {{ $b->name }}" src="{{ $b->mapEmbedUrl() }}" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>
    </div>
    @endif
    @endforeach
  </div>
</section>
