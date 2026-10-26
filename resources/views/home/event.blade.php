<section @if ($sections['event']) class="{{ $sections['event'] }}" @endif id="event">
  <div class="container">
    @include('partials.sec-title', ['title' => 'Event', 'sub' => 'Beragam event seru dan menarik bersama My Rubel.'])
    <div class="carousel">
      <button class="arrow" data-dir="-1" aria-label="Sebelumnya"><svg class="icon"><use href="#i-left"/></svg></button>
      <div class="track">
        @foreach ($activities as $a)
        <article class="event">
          <div class="event-date">
            <svg class="icon"><use href="#i-calendar"/></svg>{{ \App\Support\Site::tanggal($a->date) }}
          </div>
          <div class="event-img">
            @include('partials.img', ['src' => $a->imageUrl('photo'), 'alt' => $a->title, 'ph' => 'FOTO EVENT '.$loop->iteration])
          </div>
          <div class="event-body">
            <h3>{{ $a->title }}</h3>
            <p class="event-place"><svg class="icon"><use href="#i-pin"/></svg>{{ $a->place ?: '[Tempat]' }}</p>
            @if ($excerpt = $a->excerpt())
            <p class="event-sum">{{ $excerpt }}</p>
            @endif
            <a class="link" href="{{ \App\Support\Site::route('event.show', $a->slug) }}">Lihat Selengkapnya <svg class="icon"><use href="#i-arrow"/></svg></a>
          </div>
        </article>
        @endforeach
      </div>
      <button class="arrow" data-dir="1" aria-label="Berikutnya"><svg class="icon"><use href="#i-right"/></svg></button>
    </div>
    <div class="bar"><span></span></div>
  </div>
</section>
