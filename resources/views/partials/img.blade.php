{{-- Gambar dari disk public; jika belum diisi tampil placeholder .ph seperti di prototipe --}}
@if ($src)
<img src="{{ $src }}" alt="{{ $alt }}"@unless ($eager ?? false) loading="lazy"@endunless>
@else
<div class="ph"><span>{{ $ph }}</span></div>
@endif
