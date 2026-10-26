<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Ringkasan otomatis dari kolom content (isi lengkap): beberapa kata pertama tanpa HTML.
 */
trait HasExcerpt
{
    public function excerpt(int $words = 20): string
    {
        // isi <script>/<style> bukan teks bacaan, buang beserta tagnya
        $html = preg_replace('#<(script|style)\b[^>]*>.*?</\1>#is', '', (string) $this->content);
        $text = html_entity_decode(strip_tags(str_replace('<', ' <', $html)), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return Str::words(trim(preg_replace('/\s+/u', ' ', $text)), $words, '…');
    }
}
