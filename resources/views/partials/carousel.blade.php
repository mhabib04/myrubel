{{-- Carousel kartu poster: $id, $title, $sub, $bg, $slides (array: src, alt, ph, tag, title, small) --}}
<section @if ($bg) class="{{ $bg }}" @endif id="{{ $id }}">
  <div class="container">
    @include('partials.sec-title', ['title' => $title, 'sub' => $sub])
    <div class="carousel">
      <button class="arrow" data-dir="-1" aria-label="Sebelumnya"><svg class="icon"><use href="#i-left"/></svg></button>
      <div class="track">
        @foreach ($slides as $s)
        <figure class="slide">
          @include('partials.img', $s)
          <figcaption><span class="cap-tag">{{ $s['tag'] }}</span><b>{{ $s['title'] }}</b><small>{{ $s['small'] }}</small></figcaption>
        </figure>
        @endforeach
      </div>
      <button class="arrow" data-dir="1" aria-label="Berikutnya"><svg class="icon"><use href="#i-right"/></svg></button>
    </div>
    <div class="bar"><span></span></div>
  </div>
</section>
