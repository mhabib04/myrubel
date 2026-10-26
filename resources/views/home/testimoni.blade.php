<section @if ($sections['testimoni']) class="{{ $sections['testimoni'] }}" @endif id="testimoni">
  <div class="container">
    @include('partials.sec-title', ['title' => 'Testimoni', 'sub' => 'Cerita alumni tentang pengalaman belajar di My Rubel.'])
    <div class="carousel">
      <button class="arrow" data-dir="-1" aria-label="Sebelumnya"><svg class="icon"><use href="#i-left"/></svg></button>
      <div class="track testi-track">
        @foreach ($testimonials as $t)
        <article class="testi">
          <span class="testi-mark" aria-hidden="true">“</span>
          <p class="testi-quote">{{ $t->quote }}</p>
          <div class="testi-person">
            <div class="testi-photo">@include('partials.img', ['src' => $t->imageUrl('photo'), 'alt' => $t->name, 'ph' => 'FOTO'])</div>
            <div class="testi-who">
              <b>{{ $t->name }}</b>
              <span class="cap-tag">{{ \App\Models\Testimonial::LABELS[\App\Support\Site::unit()]['tag'] }} {{ $t->year ?: '[Tahun]' }}</span>
              @if ($t->graduated_to)
              <small>{{ $t->graduated_to }}</small>
              @endif
            </div>
          </div>
        </article>
        @endforeach
      </div>
      <button class="arrow" data-dir="1" aria-label="Berikutnya"><svg class="icon"><use href="#i-right"/></svg></button>
    </div>
    <div class="bar"><span></span></div>
  </div>
</section>
