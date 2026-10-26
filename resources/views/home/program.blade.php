<section @if ($sections['program']) class="{{ $sections['program'] }}" @endif id="program">
  <div class="container">
    @include('partials.sec-title', ['title' => 'Program Belajar', 'sub' => 'Pilih program yang sesuai dengan jenjang dan kebutuhan belajar.'])
    <div class="prog-grid" id="prog-grid">
      @foreach ($programs as $p)
      <article class="prog">
        <div class="prog-img">@include('partials.img', ['src' => $p->imageUrl('image'), 'alt' => $p->name, 'ph' => 'GAMBAR '.mb_strtoupper($p->name)])</div>
        <div class="prog-body">
          <h3>{{ $p->name }}</h3>
          <p>{{ $p->short_desc }}</p>
          <a class="link" href="{{ route('program.show', $p->slug) }}">Lihat Selengkapnya <svg class="icon"><use href="#i-arrow"/></svg></a>
        </div>
      </article>
      @endforeach
    </div>
  </div>
</section>
