<?php

namespace Tests\Feature;

use App\Filament\Pages\PengaturanMotivation;
use App\Filament\Resources\MotivationActivityResource\Pages\CreateActivity as CreateMotivationActivity;
use App\Filament\Resources\MotivationProgramResource\Pages\ListPrograms as ListMotivationPrograms;
use App\Models\Activity;
use App\Models\Program;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MotivationTest extends TestCase
{
    use RefreshDatabase;

    private string $base = 'http://motivation.test';

    /** website Bimbel ditulis lengkap: alamat pendek ikut domain request sebelumnya */
    private string $main = 'http://localhost';

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.admin_password' => 'rahasia-uji']); // domain uji: MOTIVATION_DOMAIN di phpunit.xml
        $this->seed(DatabaseSeeder::class);
    }

    public function test_beranda_motivation_terpisah_dari_website_lain(): void
    {
        Program::create(['unit' => 'motivation', 'name' => 'Outbound Sekolah', 'short_desc' => 'Permainan tim di alam terbuka.']);
        Program::create(['unit' => 'literacy', 'name' => 'Kelas Menulis Kreatif', 'short_desc' => 'Menulis.']);
        Testimonial::create(['unit' => 'motivation', 'name' => 'Pak Andi', 'quote' => 'Anak-anak jadi lebih kompak.']);

        $this->get("$this->base/")
            ->assertOk()
            ->assertSee('<title>My Rubel Motivation &amp; Outbound Center – Pasaman Barat</title>', false)
            ->assertSee('My Rubel<small class="long">Motivation &amp; Outbound Center</small>', false)
            ->assertSee('My Rubel <span>Motivation</span> &amp; Outbound Center', false)
            ->assertSee('Outbound Sekolah')
            ->assertSee('Anak-anak jadi lebih kompak.')
            ->assertDontSee('Bimbel SD')
            ->assertDontSee('Kelas Menulis Kreatif')
            ->assertDontSee('id="prestasi"', false);

        $this->get("$this->main/")->assertDontSee('Outbound Sekolah');
        $this->get("$this->base/program/bimbel-sd")->assertNotFound();
        $this->get("$this->base/karya/puisi")->assertNotFound();
    }

    public function test_detail_program_dan_event(): void
    {
        $program = Program::create(['unit' => 'motivation', 'name' => 'Pelatihan Motivasi', 'short_desc' => 'Untuk siswa kelas akhir.']);
        $event = Activity::create(['unit' => 'motivation', 'title' => 'Outbound Akbar 2026', 'date' => '2026-05-02', 'place' => 'Pantai Air Bangis',
            'content' => '<p>Seratus siswa ikut <strong>outbound</strong>.</p>']);

        $html = $this->get("$this->base/")->getContent();
        $this->assertStringContainsString("$this->base/program/{$program->slug}", $html);
        $this->assertStringContainsString("$this->base/event/{$event->slug}", $html);

        $this->get("$this->base/program/{$program->slug}")->assertOk()->assertSee('Pelatihan Motivasi');
        $this->get("$this->base/event/{$event->slug}")
            ->assertOk()
            ->assertSee('2 Mei 2026')
            ->assertSee('Pantai Air Bangis')
            ->assertSee('<strong>outbound</strong>', false);
    }

    public function test_menu_link_dan_kunjungi_juga_menuju_dua_website_lainnya(): void
    {
        $main = rtrim(config('app.url'), '/').'/';
        $sites = [
            "$this->main/" => ['http://literacy.test', 'http://motivation.test'],
            'http://literacy.test/' => [$main, 'http://motivation.test'],
            "$this->base/" => [$main, 'http://literacy.test'],
        ];

        foreach ($sites as $url => $links) {
            $html = $this->get($url)->assertOk()->getContent();
            $this->assertStringContainsString('class="nav-drop nav-drop-end"', $html);
            $this->assertStringContainsString('Kunjungi Juga', $html);
            foreach ($links as $link) {
                $this->assertSame(2, substr_count($html, "href=\"$link"), "$url harus menautkan $link di menu & Kunjungi Juga");
            }
        }

        $this->assertStringContainsString('My Rubel Motivation &amp; Outbound Center</a>', $this->get("$this->main/")->getContent());
    }

    public function test_admin_motivation_menyimpan_data_ke_unit_motivation(): void
    {
        $this->actingAs(User::first());

        Livewire::test(CreateMotivationActivity::class)
            ->fillForm(['title' => 'Camping Ceria', 'date' => '2026-06-01', 'content' => '<p>Seru.</p>'])
            ->call('create')
            ->assertHasNoFormErrors();
        $this->assertSame('motivation', Activity::where('title', 'Camping Ceria')->value('unit'));

        Program::create(['unit' => 'motivation', 'name' => 'Outbound Sekolah', 'short_desc' => 'Outbound.']);
        Livewire::test(ListMotivationPrograms::class)
            ->assertCanSeeTableRecords(Program::unit('motivation')->get())
            ->assertCanNotSeeTableRecords(Program::unit('bimbel')->get());

        Livewire::test(PengaturanMotivation::class)
            ->set('data.hero_subtitle', 'Sub judul outbound')
            ->call('save')
            ->assertHasNoErrors();
        $this->assertSame('Sub judul outbound', Setting::for('motivation')->hero_subtitle);
        $this->assertNotSame('Sub judul outbound', Setting::for('literacy')->hero_subtitle);

        foreach (['motivation/program', 'motivation/event', 'motivation/testimoni', 'motivation/pengaturan'] as $path) {
            $this->get("/admin/$path")->assertOk()->assertSee('Motivation &amp; Outbound', false);
        }
    }
}
