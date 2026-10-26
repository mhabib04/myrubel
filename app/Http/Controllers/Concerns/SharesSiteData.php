<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Setting;
use App\Support\Site;

trait SharesSiteData
{
    /**
     * Data untuk layout/header semua halaman publik. WhatsApp selalu dari pengaturan My Rubel
     * ($setting); logo, hero & media sosial dari pengaturan website yang dibuka ($siteSetting),
     * dengan logo My Rubel sebagai cadangan.
     */
    protected function shareSiteData(string $unit): void
    {
        Site::use($unit);
        $setting = Setting::current();
        $siteSetting = $unit === 'bimbel' ? $setting : Setting::for($unit);
        $sections = Site::sections();

        view()->share([
            'setting' => $setting,
            'siteSetting' => $siteSetting,
            'logo' => $siteSetting->imageUrl('logo') ?: $setting->imageUrl('logo'),
            'sections' => $sections,
            'menu' => Site::menu($sections),
            'contacts' => Site::contacts($setting),
        ]);
    }
}
