<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Info;
use App\Models\Program;
use App\Models\Work;
use App\Support\Site;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;

/**
 * sitemap.xml tiap website untuk Google Search Console: beranda dan semua halaman
 * yang dipublikasikan. Hanya berisi alamat website yang sedang dibuka.
 */
class SitemapController extends Controller
{
    public function bimbel(): Response
    {
        Site::use('bimbel');

        return $this->xml([
            ...$this->pages(Program::unit('bimbel'), 'program.show'),
            ...$this->pages(Activity::unit('bimbel'), 'event.show'),
            ...$this->pages(Info::query(), 'info.show'),
        ]);
    }

    public function literacy(): Response
    {
        Site::use('literacy');

        // daftar karya hanya untuk jenis yang sudah punya karya
        $types = Work::published()->reorder('type')->distinct()->pluck('type');

        return $this->xml([
            ...$this->pages(Program::unit('literacy'), 'program.show'),
            ...$this->pages(Activity::unit('literacy'), 'event.show'),
            ...$types->map(fn ($type) => ['loc' => Site::route('karya.index', $type), 'lastmod' => null])->all(),
            ...$this->pages(Work::query(), 'karya.show', fn (Work $work) => [$work->type, $work->slug]),
        ]);
    }

    public function motivation(): Response
    {
        Site::use('motivation');

        return $this->xml([
            ...$this->pages(Program::unit('motivation'), 'program.show'),
            ...$this->pages(Activity::unit('motivation'), 'event.show'),
        ]);
    }

    /** Halaman detail yang dipublikasikan; $params memberi parameter route (bawaan: slug). */
    private function pages(Builder $query, string $route, ?Closure $params = null): array
    {
        return $query->published()->get()
            ->map(fn ($item) => [
                'loc' => Site::route($route, $params ? $params($item) : $item->slug),
                'lastmod' => $item->updated_at,
            ])
            ->all();
    }

    private function xml(array $pages): Response
    {
        $home = ['loc' => Site::homeUrl(Site::unit()), 'lastmod' => null];

        return response()
            ->view('sitemap', ['pages' => [$home, ...$pages]])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
