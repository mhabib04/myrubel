{{-- Judul section + sub judul singkat: $title, $sub, $center (opsional) --}}
<div class="sec-title{{ ($center ?? false) ? ' center' : '' }}">
  <h2>{{ $title }}</h2>
  <p class="sec-sub">{{ $sub }}</p>
</div>
