<section @if ($sections['tentang']) class="{{ $sections['tentang'] }}" @endif id="tentang">
  <div class="container about">
    @include('partials.img', ['src' => $setting->imageUrl('about_image'), 'alt' => 'Lina Syariyenti, S.Pd., pimpinan Bimbel My Rubel', 'ph' => 'GAMBAR BU LINA'])
    <div>
      <span class="eyebrow">Tentang</span>
      @if ($setting->about_title)
      <h2>{{ $setting->about_title }}</h2>
      @endif
      @foreach (preg_split('/\R\s*\R/', trim((string) $setting->about_body), -1, PREG_SPLIT_NO_EMPTY) as $paragraph)
      <p>{{ $paragraph }}</p>
      @endforeach
      @if ($setting->quote)
      <div class="quote">“{{ $setting->quote }}”@if ($setting->quote_author)<small>{{ $setting->quote_author }}</small>@endif</div>
      @endif
    </div>
  </div>
</section>
