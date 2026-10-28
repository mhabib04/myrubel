{{-- Daftar halaman untuk mesin pencari: $pages = [['loc' => url, 'lastmod' => Carbon|null], ...] --}}
{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach ($pages as $page)
  <url>
    <loc>{{ $page['loc'] }}</loc>
@if ($page['lastmod'])
    <lastmod>{{ $page['lastmod']->toAtomString() }}</lastmod>
@endif
  </url>
@endforeach
</urlset>
