<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SharesSiteData;
use App\Models\Activity;
use App\Models\Program;
use App\Models\Testimonial;
use App\Support\Site;

/** Website My Rubel Motivation & Outbound Center (subdomain). */
class MotivationController extends Controller
{
    use SharesSiteData;

    public function home()
    {
        $this->shareSiteData('motivation');

        return view('motivation.home', [
            'programs' => Program::unit('motivation')->published()->get(),
            'activities' => Activity::unit('motivation')->published()->get(),
            'testimonials' => Testimonial::unit('motivation')->published()->get(),
        ]);
    }

    public function program(string $slug)
    {
        $program = Program::unit('motivation')->published()->where('slug', $slug)->firstOrFail();
        $this->shareSiteData('motivation');

        return view('program', ['program' => $program]);
    }

    public function event(string $slug)
    {
        $item = Activity::unit('motivation')->published()->where('slug', $slug)->firstOrFail();
        $this->shareSiteData('motivation');

        return view('detail', [
            'item' => $item, 'navActive' => 'event', 'image' => $item->imageUrl('photo'),
            'meta' => array_filter([Site::tanggal($item->date), $item->place]),
        ]);
    }
}
