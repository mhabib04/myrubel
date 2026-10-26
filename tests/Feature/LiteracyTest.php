<?php

namespace Tests\Feature;

use App\Filament\Pages\PengaturanLiteracy;
use App\Filament\Resources\LiteracyProgramResource\Pages\CreateProgram as CreateLiteracyProgram;
use App\Filament\Resources\LiteracyProgramResource\Pages\ListPrograms as ListLiteracyPrograms;
use App\Filament\Resources\WorkResource\Pages\CreateWork;
use App\Models\Achievement;
use App\Models\Program;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Work;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LiteracyTest extends TestCase
{
    use RefreshDatabase;

    private string $base = 'http://literacy.test';

    /** website Bimbel ditulis lengkap: alamat pendek ikut domain request sebelumnya */
    private string $main = 'http://localhost';

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.admin_password' => 'rahasia-uji']); // domain Literacy uji: LITERACY_DOMAIN di phpunit.xml
        $this->seed(DatabaseSeeder::class);
    }


    public function test_beranda_literacy_terpisah_dari_bimbel(): void
    {
        Program::create(['unit' => 'literacy', 'name' => 'Kelas Menulis Kreatif', 'short_desc' => 'Belajar menulis cerpen.']);

        $this->get("$this->base/")
            ->assertOk()
            ->assertSee('<title>My Rubel Literacy Center – Pasaman Barat</title>', false)
            ->assertSee('My Rubel<small>Literacy Center</small>', false)
            ->assertSee('My Rubel <span>Literacy</span> Center', false)
            ->assertSee('Kelas Menulis Kreatif')
            ->assertDontSee('Bimbel SD')               // program Bimbel tidak ikut
            ->assertDontSee('id="kenapa"', false);

        // sebaliknya, website Bimbel tidak menampilkan program Literacy
        $this->get("$this->main/")->assertSee('Bimbel SD')->assertDontSee('Kelas Menulis Kreatif');
        $this->get("$this->base/program/bimbel-sd")->assertNotFound();
    }

    public function test_karya_tampil_6_terbaru_per_jenis_dengan_menu_dropdown(): void
    {
        foreach (range(1, 8) as $i) {
            Work::create(['type' => 'puisi', 'title' => "Puisi Nomor $i", 'author' => 'Rina', 'date' => sprintf('2026-01-%02d', $i), 'content' => "<p>Bait pertama puisi $i.</p>"]);
        }
        Work::create(['type' => 'cerpen', 'title' => 'Cerpen Pertama', 'date' => '2026-02-01']);

        $html = $this->get("$this->base/")->assertOk()->getContent();

        $this->assertStringContainsString('id="puisi"', $html);
        $this->assertStringContainsString('Puisi Nomor 8', $html);
        $this->assertStringContainsString('Puisi Nomor 3', $html);
        $this->assertStringNotContainsString('Puisi Nomor 2<', $html);
        $this->assertStringContainsString('Karya oleh Rina', $html);
        $this->assertStringContainsString('Lihat yang lainnya', $html);
        $this->assertStringContainsString('class="nav-drop nav-drop-karya"', $html);
        $this->assertStringContainsString("$this->base/karya/puisi", $html);
        $this->assertStringContainsString("$this->base/karya/cerpen", $html);
        $this->assertStringNotContainsString("$this->base/karya/essay", $html); // jenis tanpa karya tidak masuk menu
        $this->assertStringNotContainsString('id="essay"', $html);
    }

    public function test_daftar_karya_20_per_halaman_dengan_nomor_halaman(): void
    {
        foreach (range(1, 25) as $i) {
            Work::create(['type' => 'artikel', 'title' => "Artikel Nomor $i", 'date' => now()->subDays($i)]);
        }

        $this->get("$this->base/karya/artikel")
            ->assertOk()
            ->assertSee('Artikel Nomor 1<', false)
            ->assertSee('Artikel Nomor 20<', false)
            ->assertDontSee('Artikel Nomor 21<', false)
            ->assertSee('class="pager"', false);

        $this->get("$this->base/karya/artikel?page=2")
            ->assertSee('Artikel Nomor 21<', false)
            ->assertSee('Artikel Nomor 25<', false);

        $this->get("$this->base/karya/lainnya")->assertNotFound();
    }

    public function test_detail_karya(): void
    {
        $work = Work::create(['type' => 'cerpen', 'title' => 'Senja di Simpang Empat', 'author' => 'Aisyah Nur', 'date' => '2026-03-14',
            'content' => '<p>Sore itu langit <strong>jingga</strong>.</p>']);

        $this->get("$this->base/karya/cerpen/{$work->slug}")
            ->assertOk()
            ->assertSee('<title>Senja di Simpang Empat – My Rubel Literacy Center Pasaman Barat</title>', false)
            ->assertSee('Cerpen')
            ->assertSee('Karya oleh Aisyah Nur')
            ->assertSee('14 Maret 2026')
            ->assertSee('<strong>jingga</strong>', false)
            ->assertSee('data-share', false)
            ->assertSee('Kembali ke Cerpen');

        $this->get("$this->base/karya/puisi/{$work->slug}")->assertNotFound(); // jenis harus cocok
    }

    public function test_logo_dan_kontak_wa_sama_dengan_my_rubel(): void
    {
        Setting::current()->update(['logo' => 'pengaturan/logo.png', 'whatsapp' => '081111111111']);

        $this->get("$this->base/")
            ->assertSee('<img src="/storage/pengaturan/logo.png" alt="Logo My Rubel">', false)
            ->assertSee('data-wa-number="6281111111111"', false);
    }

    public function test_admin_literacy_menyimpan_data_ke_unit_literacy(): void
    {
        $this->actingAs(User::first());

        Livewire::test(CreateLiteracyProgram::class)
            ->fillForm(['name' => 'Kelas Membaca', 'short_desc' => 'Membaca bersama.', 'price_unit' => 'bulan'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertSame('literacy', Program::where('name', 'Kelas Membaca')->value('unit'));

        // tabel Program Literacy hanya berisi data Literacy
        Livewire::test(ListLiteracyPrograms::class)
            ->assertCanSeeTableRecords(Program::unit('literacy')->get())
            ->assertCanNotSeeTableRecords(Program::unit('bimbel')->get());

        Livewire::test(CreateWork::class)
            ->fillForm(['type' => 'essay', 'title' => 'Pentingnya Membaca', 'author' => 'Fajar', 'date' => '2026-04-01'])
            ->call('create')
            ->assertHasNoFormErrors();
        $this->assertSame('pentingnya-membaca', Work::where('title', 'Pentingnya Membaca')->value('slug'));

        Livewire::test(PengaturanLiteracy::class)
            ->set('data.hero_subtitle', 'Sub judul baru')
            ->call('save')
            ->assertHasNoErrors();
        $this->assertSame('Sub judul baru', Setting::for('literacy')->hero_subtitle);
        $this->assertNotSame('Sub judul baru', Setting::current()->hero_subtitle);

        foreach (['literacy/program', 'literacy/prestasi', 'literacy/karya', 'literacy/event', 'literacy/testimoni', 'literacy/pengaturan'] as $path) {
            $this->get("/admin/$path")->assertOk();
        }
    }

    public function test_label_program_dan_testimoni_tanpa_istilah_bimbel(): void
    {
        $this->actingAs(User::first());

        $this->get('/admin/literacy/program/create')->assertOk()
            ->assertSee('Materi')->assertSee('Keunggulan program')
            ->assertDontSee('Mata pelajaran')->assertDontSee('Fasilitas unggulan');
        Livewire::test(CreateLiteracyProgram::class)->assertFormSet(['price_unit' => 'bulan']);
        $this->get('/admin/literacy/testimoni/create')->assertOk()
            ->assertSee('Keterangan')->assertDontSee('Tahun lulus')->assertDontSee('Lulus di');
        $this->get('/admin/program/create')->assertOk()->assertSee('Mata pelajaran')->assertSee('Fasilitas unggulan');

        $program = Program::create(['unit' => 'literacy', 'name' => 'Klub Membaca', 'short_desc' => 'Membaca.', 'subjects' => 'Resensi']);
        Testimonial::create(['unit' => 'literacy', 'name' => 'Rani', 'year' => 2026, 'quote' => 'Jadi suka menulis.']);

        $this->get("$this->base/program/{$program->slug}")->assertSee('Materi: <small>Resensi</small>', false);
        $this->get("$this->base/")->assertSee('Peserta 2026')->assertDontSee('Alumni 2026');
    }

    public function test_prestasi_bimbel_tidak_tampil_di_literacy(): void
    {
        Achievement::create(['unit' => 'literacy', 'title' => 'Juara 1 Lomba Menulis Puisi', 'level' => 'Provinsi']);

        $this->get("$this->base/")->assertSee('Juara 1 Lomba Menulis Puisi')->assertDontSee('Juara 3 Olimpiade Madrasah Indonesia');
        $this->get("$this->main/")->assertDontSee('Juara 1 Lomba Menulis Puisi');
    }
}
