{{-- Data terstruktur (schema.org) di beranda: nama, logo, kontak, alamat pusat, dan media sosial. --}}
@php
  $pusat = \App\Models\Branch::published()->where('type', 'Pusat')->whereNotNull('address')->first();
  $schema = array_filter([
      '@context' => 'https://schema.org',
      '@type' => 'EducationalOrganization',
      'name' => \App\Support\Site::name(),
      'url' => \App\Support\Site::homeUrl(\App\Support\Site::unit()),
      'logo' => \App\Support\Site::absolute($logo),
      'telephone' => $setting->whatsapp ? '+'.$setting->whatsapp : null,
      'address' => $pusat ? [
          '@type' => 'PostalAddress',
          'streetAddress' => $pusat->address,
          'addressRegion' => 'Sumatera Barat',
          'addressCountry' => 'ID',
      ] : null,
      'sameAs' => array_values(array_filter([
          $siteSetting->tiktok_url ?: $setting->tiktok_url,
          $siteSetting->instagram_url ?: $setting->instagram_url,
      ])) ?: null,
  ]);
@endphp
<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
