<section @if ($sections['info']) class="{{ $sections['info'] }}" @endif id="info">
  <div class="container">
    @include('partials.sec-title', ['title' => 'Info', 'sub' => 'Pengumuman dan informasi terbaru dari My Rubel.'])
    <div class="info-list">
      @foreach ($infos as $i)
      <article class="info-item">
        <div class="info-thumb">@include('partials.img', ['src' => $i->imageUrl('poster'), 'alt' => $i->title, 'ph' => 'POSTER'])</div>
        <div class="info-body">
          <span class="info-date"><svg class="icon"><use href="#i-calendar"/></svg>{{ \App\Support\Site::tanggal($i->date) }}</span>
          <h3>{{ $i->title }}</h3>
          @if ($excerpt = $i->excerpt())
          <p class="info-sum">{{ $excerpt }}</p>
          @endif
          <a class="link" href="{{ route('info.show', $i->slug) }}">Lihat Selengkapnya <svg class="icon"><use href="#i-arrow"/></svg></a>
        </div>
      </article>
      @endforeach
    </div>
  </div>
</section>
