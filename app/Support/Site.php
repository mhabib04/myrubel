<?php

namespace App\Support;

use App\Models\Achievement;
use App\Models\Activity;
use App\Models\Alumnus;
use App\Models\Branch;
use App\Models\Gallery;
use App\Models\Info;
use App\Models\Program;
use App\Models\Setting;
use App\Models\Teacher;
use App\Models\Testimonial;
use Illuminate\Support\HtmlString;

/**
 * Susunan section & menu beranda, dan format teks sorotan.
 */
class Site
{
    /** Urutan section beranda setelah hero. Testimoni & Pengajar tidak masuk menu atas (MENU). */
    public const SECTIONS = [
        'kenapa' => null,
        'program' => Program::class,
        'prestasi' => Achievement::class,
        'alumni' => Alumnus::class,
        'testimoni' => Testimonial::class,
        'event' => Activity::class,
        'info' => Info::class,
        'galeri' => Gallery::class,
        'tentang' => null,
        'pengajar' => Teacher::class,
        'lokasi' => Branch::class,
    ];

    public const MENU = [
        'beranda' => 'Beranda', 'program' => 'Program', 'prestasi' => 'Prestasi', 'alumni' => 'Alumni',
        'event' => 'Event', 'info' => 'Info', 'galeri' => 'Galeri', 'tentang' => 'Tentang', 'lokasi' => 'Lokasi',
    ];

    /**
     * Section yang tampil beserta latarnya. Section koleksi tanpa data yang dipublikasikan disembunyikan,
     * dan latar bg-soft diselang-seling ulang supaya tidak ada dua section berwarna sama berdampingan.
     *
     * @return array<string, string> id section => kelas latar ('bg-soft' atau '')
     */
    public static function sections(): array
    {
        $ids = array_keys(array_filter(
            static::SECTIONS,
            fn (?string $model) => $model === null || $model::query()->where('is_published', true)->exists(),
        ));
        $visible = [];
        foreach ($ids as $i => $id) {
            $visible[$id] = $i % 2 === 0 ? 'bg-soft' : '';
        }

        return $visible;
    }

    /** @return array<string, string> anchor => label menu */
    public static function menu(array $sections): array
    {
        return array_filter(static::MENU, fn ($label, $id) => $id === 'beranda' || isset($sections[$id]), ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Kontak WhatsApp untuk tombol Konsultasi Gratis: nomor utama (Pengaturan) lalu admin
     * tiap cabang yang sudah buka. Nomor yang sama hanya ditampilkan sekali.
     *
     * @return list<array{name: string, note: string, number: string}>
     */
    public static function contacts(Setting $setting): array
    {
        $contacts = [];
        if ($setting->whatsapp) {
            $contacts[] = ['name' => $setting->whatsapp_name ?: 'Kontak utama', 'note' => 'Kontak utama', 'number' => $setting->whatsapp];
        }
        $branches = Branch::published()->where('is_coming_soon', false)->whereNotNull('whatsapp')->get();
        foreach ($branches as $b) {
            $contacts[] = ['name' => 'Admin '.$b->name, 'note' => $b->type === 'Pusat' ? 'Admin pusat' : 'Admin cabang', 'number' => $b->whatsapp];
        }

        return collect($contacts)->unique('number')->values()->all();
    }

    /** Tanggal seperti "7 Februari 2026"; placeholder jika kosong. */
    public static function tanggal(?\DateTimeInterface $date): string
    {
        return $date ? \Illuminate\Support\Carbon::instance($date)->locale('id')->translatedFormat('j F Y') : '[Tanggal]';
    }

    /** Teks yang diapit *bintang* diberi <span> (sorotan kuning di desain); sisanya di-escape. */
    public static function highlight(?string $text): HtmlString
    {
        return new HtmlString(preg_replace('/\*(.+?)\*/u', '<span>$1</span>', e((string) $text)));
    }
}
