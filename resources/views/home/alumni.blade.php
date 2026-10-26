<section @if ($sections['alumni']) class="{{ $sections['alumni'] }}" @endif id="alumni">
  <div class="container">
    @include('partials.sec-title', ['title' => 'Alumni', 'sub' => 'Jejak alumni My Rubel di kampus pilihan mereka.'])
    <div class="carousel">
      <button class="arrow" data-dir="-1" aria-label="Sebelumnya"><svg class="icon"><use href="#i-left"/></svg></button>
      <div class="track alumni-track">
        @foreach ($alumni as $a)
        <article class="alum">
          <div class="alum-photo">@include('partials.img', ['src' => $a->imageUrl('photo'), 'alt' => $a->name, 'ph' => 'FOTO ALUMNI '.$loop->iteration])</div>
          <div class="alum-body">
            <span class="cap-tag">Alumni {{ $a->graduation_year ?: '[Tahun]' }}</span>
            <h3>{{ $a->name }}</h3>
            <p class="alum-campus">{{ $a->campus ?: '[Kampus/Sekolah]' }}</p>
            <p class="alum-major">{{ $a->major ?: '[Jurusan]' }}</p>
          </div>
        </article>
        @endforeach
      </div>
      <button class="arrow" data-dir="1" aria-label="Berikutnya"><svg class="icon"><use href="#i-right"/></svg></button>
    </div>
    <div class="bar"><span></span></div>
  </div>
</section>
