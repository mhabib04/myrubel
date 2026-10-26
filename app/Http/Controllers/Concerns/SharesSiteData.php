<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Setting;
use App\Support\Site;

trait SharesSiteData
{
    /**
     * Data untuk layout/header semua halaman publik. Logo & WhatsApp selalu dari pengaturan
     * My Rubel ($setting); hero & media sosial dari pengaturan website yang dibuka ($siteSetting).
     */
    protected function shareSiteData(string $unit): void
    {
        Site::use($unit);
        $setting = Setting::current();
        $sections = Site::sections();

        view()->share([
            'setting' => $setting,
            'siteSetting' => $unit === 'bimbel' ? $setting : Setting::for($unit),
            'sections' => $sections,
            'menu' => Site::menu($sections),
            'contacts' => Site::contacts($setting),
        ]);
    }
}
