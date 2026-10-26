{{-- Nomor halaman untuk daftar panjang (mis. daftar karya) --}}
@if ($paginator->hasPages())
<nav class="pager" aria-label="Nomor halaman">
  @if ($paginator->onFirstPage())
  <span class="pager-btn disabled" aria-disabled="true"><svg class="icon"><use href="#i-left"/></svg>Sebelumnya</span>
  @else
  <a class="pager-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev"><svg class="icon"><use href="#i-left"/></svg>Sebelumnya</a>
  @endif

  <span class="pager-pages">
    @foreach ($elements as $element)
      @if (is_string($element))
      <span class="pager-num dots">{{ $element }}</span>
      @else
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
          <span class="pager-num current" aria-current="page">{{ $page }}</span>
          @else
          <a class="pager-num" href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach
      @endif
    @endforeach
  </span>

  @if ($paginator->hasMorePages())
  <a class="pager-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya<svg class="icon"><use href="#i-right"/></svg></a>
  @else
  <span class="pager-btn disabled" aria-disabled="true">Berikutnya<svg class="icon"><use href="#i-right"/></svg></span>
  @endif
</nav>
@endif
