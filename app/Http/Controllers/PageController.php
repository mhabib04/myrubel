<?php

namespace App\Http\Controllers;

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
use App\Support\Site;

class PageController extends Controller
{
    /** Pengaturan & daftar section dipakai layout/header di semua halaman publik. */
    private function shareSiteData(): void
    {
        $setting = Setting::current();

        view()->share(['setting' => $setting, 'sections' => Site::sections(), 'contacts' => Site::contacts($setting)]);
    }

    public function home()
    {
        $this->shareSiteData();

        return view('home', [
            'programs' => Program::published()->get(),
            'achievements' => Achievement::published()->get(),
            'alumni' => Alumnus::published()->get(),
            'testimonials' => Testimonial::published()->get(),
            'teachers' => Teacher::published()->get(),
            'activities' => Activity::published()->get(),
            'infos' => Info::published()->limit(6)->get(), // 6 info terbaru
            'galleries' => Gallery::published()->get(),
            'branches' => Branch::published()->get(),
        ]);
    }

    public function program(string $slug)
    {
        $program = Program::published()->where('slug', $slug)->firstOrFail();
        $this->shareSiteData();

        return view('program', ['program' => $program]);
    }

    public function event(string $slug)
    {
        $item = Activity::published()->where('slug', $slug)->firstOrFail();
        $this->shareSiteData();

        return view('detail', [
            'item' => $item, 'navActive' => 'event', 'image' => $item->imageUrl('photo'),
            'meta' => array_filter([Site::tanggal($item->date), $item->place]),
        ]);
    }

    public function info(string $slug)
    {
        $item = Info::published()->where('slug', $slug)->firstOrFail();
        $this->shareSiteData();

        return view('detail', [
            'item' => $item, 'navActive' => 'info', 'image' => $item->imageUrl('poster'),
            'meta' => [Site::tanggal($item->date)],
        ]);
    }
}
