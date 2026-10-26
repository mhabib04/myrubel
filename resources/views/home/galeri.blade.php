<section @if ($sections['galeri']) class="{{ $sections['galeri'] }}" @endif id="galeri">
  <div class="container">
    @include('partials.sec-title', ['title' => 'Galeri', 'sub' => 'Momen belajar dan kebersamaan di My Rubel.'])
    <div class="carousel">
      <button class="arrow" data-dir="-1" aria-label="Sebelumnya"><svg class="icon"><use href="#i-left"/></svg></button>
      <div class="track wide">
        @foreach ($galleries as $g)
        <figure class="slide">
          @include('partials.img', ['src' => $g->imageUrl('image'), 'alt' => $g->caption ?: 'Galeri My Rubel', 'ph' => 'KOLASE FOTO '.$loop->iteration])
          @if ($g->caption)
          <figcaption><b>{{ $g->caption }}</b></figcaption>
          @endif
        </figure>
        @endforeach
      </div>
      <button class="arrow" data-dir="1" aria-label="Berikutnya"><svg class="icon"><use href="#i-right"/></svg></button>
    </div>
  </div>
</section>
