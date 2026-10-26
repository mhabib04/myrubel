<section @if ($sections['pengajar']) class="{{ $sections['pengajar'] }}" @endif id="pengajar">
  <div class="container">
    @include('partials.sec-title', ['title' => 'Pengajar', 'sub' => 'Tentor berpengalaman yang membimbing siswa My Rubel.'])
    <div class="carousel">
      <button class="arrow" data-dir="-1" aria-label="Sebelumnya"><svg class="icon"><use href="#i-left"/></svg></button>
      <div class="track teacher-track">
        @foreach ($teachers as $t)
        <article class="teacher">
          <div class="teacher-photo">@include('partials.img', ['src' => $t->imageUrl('photo'), 'alt' => $t->name, 'ph' => 'FOTO PENGAJAR'])</div>
          <div class="teacher-body">
            @if ($t->subject)
            <span class="cap-tag">{{ $t->subject }}</span>
            @endif
            <h3>{{ $t->name }}</h3>
            @if ($t->almamater)
            <p class="teacher-alma">Alumni {{ $t->almamater }}</p>
            @endif
            @if ($t->motto)
            <p class="teacher-motto">“{{ $t->motto }}”</p>
            @endif
          </div>
        </article>
        @endforeach
      </div>
      <button class="arrow" data-dir="1" aria-label="Berikutnya"><svg class="icon"><use href="#i-right"/></svg></button>
    </div>
    <div class="bar"><span></span></div>
  </div>
</section>
