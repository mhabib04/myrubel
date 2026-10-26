<?php

namespace Tests\Feature;

use App\Models\Achievement;
use App\Models\Branch;
use App\Models\Gallery;
use App\Models\Program;
use App\Models\Setting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.admin_password' => 'rahasia-uji']);
        $this->seed(DatabaseSeeder::class);
    }

    public function test_beranda_menampilkan_data_awal(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Rumah Belajar <span>Pertama</span> di Pasaman Barat', false)
            ->assertSee('SMART, <span>MOTIVATED,</span> AND INSPIRED', false)
            ->assertSeeInOrder(['Bimbel SD', 'Bimbel SMP', 'Bimbel SMA', 'Bahasa Inggris', 'Olimpiade', 'Calistung'])
            ->assertSee('Juara 3 Olimpiade Madrasah Indonesia, Cabang IPA')
            ->assertSee('7 Februari 2026')
            ->assertSee('/event/3rd-my-rubel-competition', false)
            ->assertSee('Alasan My Rubel menjadi pilihan belajar sejak 2016.')
            ->assertSee('[Nama siswa] · [Tahun]')
            ->assertSee('data-wa-number="6281234567890"', false)
            ->assertSee('GAMBAR HERO (Bu Lina)');
    }

    public function test_section_kosong_disembunyikan_beserta_menunya(): void
    {
        $this->get('/')
            ->assertDontSee('id="alumni"', false)
            ->assertDontSee('href="#alumni"', false)
            ->assertDontSee('id="galeri"', false)
            ->assertSee('href="#prestasi"', false);

        Gallery::create(['caption' => 'Suasana belajar di kelas']);

        $this->get('/')
            ->assertSee('id="galeri"', false)
            ->assertSee('href="#galeri"', false)
            ->assertSee('KOLASE FOTO 1');
    }

    public function test_data_disembunyikan_dan_urutan_mengikuti_admin(): void
    {
        Program::where('slug', 'calistung')->update(['sort_order' => 0]);
        Program::where('slug', 'olimpiade')->update(['is_published' => false]);

        $this->get('/')
            ->assertSeeInOrder(['Calistung', 'Bimbel SD'])
            ->assertDontSee('/program/olimpiade');

        $this->get('/program/olimpiade')->assertNotFound();
    }

    public function test_gambar_terisi_menggantikan_placeholder(): void
    {
        Achievement::published()->first()->update(['poster' => 'prestasi/juara.jpg']);

        $this->get('/')
            ->assertSee('/storage/prestasi/juara.jpg', false)
            ->assertDontSee('POSTER PRESTASI 1')
            ->assertSee('POSTER PRESTASI 2');
    }

    public function test_teks_dari_admin_di_escape(): void
    {
        Setting::current()->update(['hero_title' => '<script>x</script> *Hebat*']);

        $this->get('/')
            ->assertDontSee('<script>x</script>', false)
            ->assertSee('&lt;script&gt;x&lt;/script&gt; <span>Hebat</span>', false);
    }

    public function test_cabang_segera_hadir_tanpa_peta(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertStringContainsString('Peta My Rubel Simpang Empat', $html);
        $this->assertStringNotContainsString('Peta My Rubel Kinali', $html);
        $this->assertStringContainsString('Segera hadir', $html);

        Branch::where('name', 'Jambak')->update(['whatsapp' => '628111']);
        $this->get('/')->assertSee('data-wa-number="628111"', false);
    }

    public function test_logo_menggantikan_kotak_mr_dan_jadi_ikon_tab(): void
    {
        $this->get('/')
            ->assertSee('<span class="mark">MR</span>', false)
            ->assertDontSee('rel="icon"', false);

        Setting::current()->update(['logo' => 'pengaturan/logo.png']);

        foreach (['/', '/program/sd'] as $url) {
            $this->get($url)
                ->assertSee('<img src="/storage/pengaturan/logo.png" alt="Logo My Rubel">', false)
                ->assertSee('<link rel="icon" href="/storage/pengaturan/logo.png">', false)
                ->assertDontSee('<span class="mark">MR</span>', false);
        }
    }

    public function test_detail_program(): void
    {
        $this->get('/program/sd')
            ->assertOk()
            ->assertSee('<title>Bimbel SD – Bimbel My Rubel Pasaman Barat</title>', false)
            ->assertSee('<h1 id="p-title">Bimbel SD</h1>', false)
            ->assertSee('data-share', false)
            ->assertSee('Rp1.500.000<small>/semester</small>', false)
            ->assertSee('Mata pelajaran: <small>Matematika, IPAS, Bahasa Inggris, Bahasa Indonesia</small>', false)
            ->assertSee('Free WiFi sepuasnya')
            ->assertSee('data-wa="Halo My Rubel, saya mau tanya program Bimbel SD."', false);

        $this->get('/program/smp')->assertSee('[Harga]<small>/semester</small>', false);
        $this->get('/program/tidak-ada')->assertNotFound();
    }
}
