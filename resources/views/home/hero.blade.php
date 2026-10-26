<section class="hero" id="beranda">
  <div class="container">
    <div>
      <h1>{{ \App\Support\Site::highlight($setting->hero_title) }}</h1>
      @if ($setting->hero_subtitle)
      <p class="sub">{{ $setting->hero_subtitle }}</p>
      @endif
      <div class="hero-cta">
        <a class="btn btn-wa" data-wa-pick data-wa="Halo My Rubel, saya mau konsultasi gratis."><svg class="icon"><use href="#i-chat"/></svg>Konsultasi Gratis (WA)</a>
        <a href="#program" class="btn btn-navy">Lihat Program</a>
      </div>
      @if ($setting->hero_tagline)
      <p class="tagline">{{ \App\Support\Site::highlight($setting->hero_tagline) }}</p>
      @endif
    </div>
    <div class="hero-img">
      @include('partials.img', ['src' => $setting->imageUrl('hero_image'), 'alt' => 'Bimbel My Rubel Pasaman Barat', 'ph' => 'GAMBAR HERO (Bu Lina)', 'eager' => true])
    </div>
  </div>
</section>
