{{-- Pilihan kontak WhatsApp untuk tombol bertanda data-wa-pick; hanya dipasang jika ada >1 nomor --}}
<dialog class="wa-pick" id="wa-pick" aria-labelledby="wa-pick-title">
  <div class="wa-pick-box">
    <div class="wa-pick-head">
      <div>
        <h3 id="wa-pick-title">Hubungi via WhatsApp</h3>
        <p>Pilih kontak yang ingin Anda hubungi.</p>
      </div>
      <button class="wa-pick-close" type="button" aria-label="Tutup"><svg class="icon"><use href="#i-close"/></svg></button>
    </div>
    <ul>
      @foreach ($contacts as $c)
      <li>
        <a class="wa-pick-item" data-number="{{ $c['number'] }}" target="_blank" rel="noopener">
          <span class="wa-pick-ic"><svg class="icon"><use href="#i-chat"/></svg></span>
          <span class="wa-pick-text"><b>{{ $c['name'] }}</b><small>{{ $c['note'] }}</small></span>
          <svg class="icon"><use href="#i-arrow"/></svg>
        </a>
      </li>
      @endforeach
    </ul>
  </div>
</dialog>
