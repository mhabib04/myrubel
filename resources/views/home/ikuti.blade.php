@php
  $tiktok = $siteSetting->tiktok_url ?: $setting->tiktok_url;
  $instagram = $siteSetting->instagram_url ?: $setting->instagram_url;
@endphp
<div class="follow">
  <h3>Kunjungi Juga</h3>
  <div class="other-sites">
    @foreach (\App\Support\Site::others() as $name => $url)
    <a href="{{ $url }}">{{ $name }}<svg class="icon"><use href="#i-arrow"/></svg></a>
    @endforeach
  </div>
  <h3>Ikuti Kami</h3>
  <div class="socials">
    <a href="{{ $tiktok ?: '#' }}" aria-label="TikTok"@if ($tiktok) target="_blank" rel="noopener"@endif><svg class="icon" style="fill:currentColor;stroke:none"><use href="#i-tiktok"/></svg></a>
    <a href="{{ $instagram ?: '#' }}" aria-label="Instagram"@if ($instagram) target="_blank" rel="noopener"@endif><svg class="icon"><use href="#i-ig"/></svg></a>
  </div>
</div>
