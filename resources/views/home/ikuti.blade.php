<div class="follow">
  <h3>Ikuti Kami</h3>
  <div class="socials">
    <a href="{{ $setting->tiktok_url ?: '#' }}" aria-label="TikTok"@if ($setting->tiktok_url) target="_blank" rel="noopener"@endif><svg class="icon" style="fill:currentColor;stroke:none"><use href="#i-tiktok"/></svg></a>
    <a href="{{ $setting->instagram_url ?: '#' }}" aria-label="Instagram"@if ($setting->instagram_url) target="_blank" rel="noopener"@endif><svg class="icon"><use href="#i-ig"/></svg></a>
  </div>
</div>
