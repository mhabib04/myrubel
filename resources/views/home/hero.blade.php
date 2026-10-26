<section class="hero" id="beranda">
  <div class="container">
    <div>
      <h1>{{ \App\Support\Site::highlight($siteSetting->hero_title) }}</h1>
      @if ($siteSetting->hero_subtitle)
      <p class="sub">{{ $siteSetting->hero_subtitle }}</p>
      @endif
      <div class="hero-cta">
        <a class="btn btn-wa" data-wa-pick data-wa="Halo My Rubel, saya mau konsultasi gratis."><svg class="icon"><use href="#i-chat"/></svg>Konsultasi Gratis (WA)</a>
        <a href="#program" class="btn btn-navy">Lihat Program</a>
      </div>
      @if ($siteSetting->hero_tagline)
      <p class="tagline">{{ \App\Support\Site::highlight($siteSetting->hero_tagline) }}</p>
      @endif
    </div>
    <div class="hero-img">
      @include('partials.img', ['src' => $siteSetting->imageUrl('hero_image'), 'alt' => \App\Support\Site::name().' Pasaman Barat', 'ph' => \App\Support\Site::unit() === 'bimbel' ? 'GAMBAR HERO (Bu Lina)' : 'GAMBAR HERO', 'eager' => true])
    </div>
  </div>
</section>
