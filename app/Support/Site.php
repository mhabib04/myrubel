<?php

namespace App\Support;

use App\Models\Achievement;
use App\Models\Activity;
use App\Models\Alumnus;
use App\Models\Branch;
use App\Models\Concerns\HasUnit;
use App\Models\Gallery;
use App\Models\Info;
use App\Models\Program;
use App\Models\Setting;
use App\Models\Teacher;
use App\Models\Testimonial;
use App\Models\Work;
use Illuminate\Support\HtmlString;

/**
 * Website (unit) yang sedang dibuka beserta susunan section & menunya, dan format teks sorotan.
 * Satu aplikasi melayani beberapa website: 'bimbel' (myrubel.com), 'literacy' dan 'motivation' (subdomain).
 */
class Site
{
    /** nama website, dua baris tulisan di header, dan awalan nama route */
    public const UNITS = [
        'bimbel' => ['name' => 'Bimbel My Rubel', 'brand' => ['Bimbel', 'My Rubel'], 'route' => ''],
        'literacy' => ['name' => 'My Rubel Literacy Center', 'brand' => ['My Rubel', 'Literacy Center'], 'route' => 'literacy.'],
        'motivation' => ['name' => 'My Rubel Motivation & Outbound Center', 'brand' => ['My Rubel', 'Motivation & Outbound Center'], 'route' => 'motivation.'],
    ];

    /** Urutan section beranda setelah hero. Section bernilai null selalu tampil. */
    public const SECTIONS = [
        'bimbel' => [
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
        ],
        'literacy' => [
            'program' => Program::class,
            'prestasi' => Achievement::class,
            'puisi' => Work::class,
            'cerpen' => Work::class,
            'essay' => Work::class,
            'artikel' => Work::class,
            'event' => Activity::class,
            'testimoni' => Testimonial::class,
        ],
        'motivation' => [
            'program' => Program::class,
            'event' => Activity::class,
            'testimoni' => Testimonial::class,
        ],
    ];

    /** Menu atas. 'karya' di Literacy berupa dropdown ke halaman daftar tiap jenis karya. */
    public const MENU = [
        'bimbel' => [
            'beranda' => 'Beranda', 'program' => 'Program', 'prestasi' => 'Prestasi', 'alumni' => 'Alumni',
            'event' => 'Event', 'info' => 'Info', 'galeri' => 'Galeri', 'tentang' => 'Tentang', 'lokasi' => 'Lokasi',
        ],
        'literacy' => [
            'beranda' => 'Beranda', 'program' => 'Program', 'prestasi' => 'Prestasi', 'karya' => 'Karya',
            'event' => 'Event', 'testimoni' => 'Testimoni',
        ],
        'motivation' => [
            'beranda' => 'Beranda', 'program' => 'Program', 'event' => 'Event', 'testimoni' => 'Testimoni',
        ],
    ];

    private static string $unit = 'bimbel';

    /** Dipanggil controller di awal setiap halaman publik. */
    public static function use(string $unit): void
    {
        static::$unit = $unit;
    }

    public static function unit(): string
    {
        return static::$unit;
    }

    public static function name(): string
    {
        return static::UNITS[static::$unit]['name'];
    }

    /** @return array{0: string, 1: string} */
    public static function brand(): array
    {
        return static::UNITS[static::$unit]['brand'];
    }

    /** URL route milik website yang sedang dibuka, mis. Site::route('program.show', $slug). */
    public static function route(string $name, mixed $parameters = []): string
    {
        return route(static::UNITS[static::$unit]['route'].$name, $parameters);
    }

    public static function isHome(): bool
    {
        return request()->routeIs(static::UNITS[static::$unit]['route'].'home');
    }

    /**
     * Section yang tampil beserta latarnya. Section koleksi tanpa data yang dipublikasikan disembunyikan,
     * dan latar bg-soft diselang-seling ulang supaya tidak ada dua section berwarna sama berdampingan.
     *
     * @return array<string, string> id section => kelas latar ('bg-soft' atau '')
     */
    public static function sections(): array
    {
        $unit = static::$unit;
        $ids = [];
        foreach (static::SECTIONS[$unit] as $id => $model) {
            if ($model === null) {
                $ids[] = $id;

                continue;
            }
            $query = $model::query()->where('is_published', true);
            if ($model === Work::class) {
                $query->where('type', $id);
            } elseif (in_array(HasUnit::class, class_uses_recursive($model), true)) {
                $query->unit($unit);
            }
            if ($query->exists()) {
                $ids[] = $id;
            }
        }

        $visible = [];
        foreach ($ids as $i => $id) {
            $visible[$id] = $i % 2 === 0 ? 'bg-soft' : '';
        }

        return $visible;
    }

    /** @return array<string, string> anchor => label menu */
    public static function menu(array $sections): array
    {
        return array_filter(
            static::MENU[static::$unit],
            fn ($label, $id) => $id === 'beranda' || isset($sections[$id]) || ($id === 'karya' && static::workTypes($sections)),
            ARRAY_FILTER_USE_BOTH,
        );
    }

    /** Jenis karya yang punya data, untuk isi dropdown menu Karya. @return array<string, string> */
    public static function workTypes(array $sections): array
    {
        return collect(Work::TYPES)
            ->filter(fn ($type, $key) => isset($sections[$key]))
            ->map(fn ($type) => $type[0])
            ->all();
    }

    /**
     * Kontak WhatsApp untuk tombol Konsultasi Gratis: nomor utama (Pengaturan) lalu admin
     * tiap cabang yang sudah buka. Nomor yang sama hanya ditampilkan sekali. Sama untuk semua website.
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
