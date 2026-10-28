<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Info;
use App\Models\Program;
use App\Models\Work;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.admin_password' => 'rahasia-uji']); // domain uji: LITERACY_DOMAIN & MOTIVATION_DOMAIN di phpunit.xml
        $this->seed(DatabaseSeeder::class);
    }

    public function test_sitemap_bimbel_berisi_halaman_yang_dipublikasikan_saja(): void
    {
        $root = rtrim(config('app.url'), '/');
        $info = Info::create(['title' => 'Libur Semester']);
        Info::create(['title' => 'Draf Pengumuman', 'is_published' => false]);
        Program::create(['unit' => 'literacy', 'name' => 'Klub Membaca', 'short_desc' => 'Membaca.']);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false)
            ->assertSee("<loc>$root/</loc>", false)
            ->assertSee("<loc>$root/program/sd</loc>", false)
            ->assertSee("<loc>$root/info/{$info->slug}</loc>", false)
            ->assertDontSee('draf-pengumuman')
            ->assertDontSee('klub-membaca');
    }

    public function test_sitemap_literacy_berisi_karya_dan_daftar_per_jenis(): void
    {
        $program = Program::create(['unit' => 'literacy', 'name' => 'Klub Membaca', 'short_desc' => 'Membaca.']);
        $work = Work::create(['type' => 'puisi', 'title' => 'Hujan Pagi', 'date' => '2026-04-01']);

        $this->get('http://literacy.test/sitemap.xml')
            ->assertOk()
            ->assertSee('<loc>http://literacy.test</loc>', false)
            ->assertSee("<loc>http://literacy.test/program/{$program->slug}</loc>", false)
            ->assertSee('<loc>http://literacy.test/karya/puisi</loc>', false)
            ->assertSee("<loc>http://literacy.test/karya/puisi/{$work->slug}</loc>", false)
            ->assertDontSee('/karya/cerpen<', false) // jenis tanpa karya tidak dicantumkan
            ->assertDontSee('/program/sd<', false);
    }

    public function test_sitemap_motivation_hanya_berisi_halaman_motivation(): void
    {
        $event = Activity::create(['unit' => 'motivation', 'title' => 'Camping Ceria']);

        $this->get('http://motivation.test/sitemap.xml')
            ->assertOk()
            ->assertSee("<loc>http://motivation.test/event/{$event->slug}</loc>", false)
            ->assertDontSee('/info/', false)
            ->assertDontSee('/program/sd<', false);
    }
}
