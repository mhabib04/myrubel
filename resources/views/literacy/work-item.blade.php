{{-- Satu baris karya (bentuk sama seperti daftar Info). $work --}}
@use('App\Support\Site')
<article class="info-item">
  <div class="info-thumb">@include('partials.img', ['src' => $work->imageUrl('poster'), 'alt' => $work->title, 'ph' => 'POSTER'])</div>
  <div class="info-body">
    <span class="info-date"><svg class="icon"><use href="#i-calendar"/></svg>{{ Site::tanggal($work->date) }}</span>
    <h3>{{ $work->title }}</h3>
    @if ($work->author)
    <p class="work-author">Karya oleh {{ $work->author }}</p>
    @endif
    @if ($excerpt = $work->excerpt())
    <p class="info-sum">{{ $excerpt }}</p>
    @endif
    <a class="link" href="{{ Site::route('karya.show', [$work->type, $work->slug]) }}">Lihat Selengkapnya <svg class="icon"><use href="#i-arrow"/></svg></a>
  </div>
</article>
