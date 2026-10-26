<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SharesSiteData;
use App\Models\Achievement;
use App\Models\Activity;
use App\Models\Alumnus;
use App\Models\Branch;
use App\Models\Gallery;
use App\Models\Info;
use App\Models\Program;
use App\Models\Teacher;
use App\Models\Testimonial;
use App\Support\Site;

/** Website utama: Bimbel My Rubel. */
class PageController extends Controller
{
    use SharesSiteData;

    public function home()
    {
        $this->shareSiteData('bimbel');

        return view('home', [
            'programs' => Program::unit('bimbel')->published()->get(),
            'achievements' => Achievement::unit('bimbel')->published()->get(),
            'alumni' => Alumnus::published()->get(),
            'testimonials' => Testimonial::unit('bimbel')->published()->get(),
            'teachers' => Teacher::published()->get(),
            'activities' => Activity::unit('bimbel')->published()->get(),
            'infos' => Info::published()->limit(6)->get(), // 6 info terbaru
            'galleries' => Gallery::published()->get(),
            'branches' => Branch::published()->get(),
        ]);
    }

    public function program(string $slug)
    {
        $program = Program::unit('bimbel')->published()->where('slug', $slug)->firstOrFail();
        $this->shareSiteData('bimbel');

        return view('program', ['program' => $program]);
    }

    public function event(string $slug)
    {
        $item = Activity::unit('bimbel')->published()->where('slug', $slug)->firstOrFail();
        $this->shareSiteData('bimbel');

        return view('detail', [
            'item' => $item, 'navActive' => 'event', 'image' => $item->imageUrl('photo'),
            'meta' => array_filter([Site::tanggal($item->date), $item->place]),
        ]);
    }

    public function info(string $slug)
    {
        $item = Info::published()->where('slug', $slug)->firstOrFail();
        $this->shareSiteData('bimbel');

        return view('detail', [
            'item' => $item, 'navActive' => 'info', 'image' => $item->imageUrl('poster'),
            'meta' => [Site::tanggal($item->date)],
        ]);
    }
}
