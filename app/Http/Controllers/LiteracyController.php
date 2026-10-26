<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SharesSiteData;
use App\Models\Achievement;
use App\Models\Activity;
use App\Models\Program;
use App\Models\Testimonial;
use App\Models\Work;
use App\Support\Site;

/** Website My Rubel Literacy Center (subdomain). */
class LiteracyController extends Controller
{
    use SharesSiteData;

    public function home()
    {
        $this->shareSiteData('literacy');

        return view('literacy.home', [
            'programs' => Program::unit('literacy')->published()->get(),
            'achievements' => Achievement::unit('literacy')->published()->get(),
            // 6 karya terbaru untuk setiap jenis
            'works' => collect(array_keys(Work::TYPES))
                ->mapWithKeys(fn ($type) => [$type => Work::where('type', $type)->published()->limit(6)->get()]),
            'activities' => Activity::unit('literacy')->published()->get(),
            'testimonials' => Testimonial::unit('literacy')->published()->get(),
        ]);
    }

    public function program(string $slug)
    {
        $program = Program::unit('literacy')->published()->where('slug', $slug)->firstOrFail();
        $this->shareSiteData('literacy');

        return view('program', ['program' => $program]);
    }

    public function event(string $slug)
    {
        $item = Activity::unit('literacy')->published()->where('slug', $slug)->firstOrFail();
        $this->shareSiteData('literacy');

        return view('detail', [
            'item' => $item, 'navActive' => 'event', 'image' => $item->imageUrl('photo'),
            'meta' => array_filter([Site::tanggal($item->date), $item->place]),
        ]);
    }

    /** Daftar karya satu jenis, 20 per halaman. */
    public function works(string $type)
    {
        $this->shareSiteData('literacy');

        return view('literacy.works', [
            'type' => $type,
            'works' => Work::where('type', $type)->published()->paginate(20),
        ]);
    }

    public function work(string $type, string $slug)
    {
        $work = Work::where('type', $type)->published()->where('slug', $slug)->firstOrFail();
        $this->shareSiteData('literacy');

        return view('literacy.work', ['work' => $work]);
    }
}
