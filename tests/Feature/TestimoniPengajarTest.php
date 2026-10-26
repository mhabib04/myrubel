<?php

namespace Tests\Feature;

use App\Models\Teacher;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimoniPengajarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.admin_password' => 'rahasia-uji']);
        $this->seed(DatabaseSeeder::class);
    }

    public function test_section_tersembunyi_selama_belum_ada_data(): void
    {
        $this->get('/')
            ->assertDontSee('id="testimoni"', false)
            ->assertDontSee('id="pengajar"', false);
    }

    public function test_testimoni_tampil_setelah_alumni_urut_tahun_terbaru(): void
    {
        Testimonial::create(['name' => 'Andi', 'year' => 2022, 'graduated_to' => 'Universitas Andalas', 'quote' => 'Belajar di sini seru.']);
        Testimonial::create(['name' => 'Bunga', 'year' => 2025, 'graduated_to' => 'Institut Teknologi Bandung', 'quote' => 'Tentornya sabar.']);
        \App\Models\Alumnus::create(['name' => 'Alumni Contoh', 'graduation_year' => 2024]);

        $this->get('/')
            ->assertSee('Cerita alumni tentang pengalaman belajar di My Rubel.')
            ->assertSeeInOrder(['id="alumni"', 'id="testimoni"', 'id="event"'], false)
            ->assertSeeInOrder(['Bunga', 'Andi'])
            ->assertSeeInOrder(['Bunga', 'Alumni 2025', 'Institut Teknologi Bandung'])
            ->assertSee('Tentornya sabar.');
    }

    public function test_pengajar_tampil_setelah_tentang_sesuai_urutan(): void
    {
        Teacher::create(['name' => 'Pak Budi', 'subject' => 'Matematika', 'almamater' => 'Universitas Negeri Padang', 'motto' => 'Belajar itu menyenangkan.', 'sort_order' => 2]);
        Teacher::create(['name' => 'Bu Sari', 'subject' => 'Bahasa Inggris', 'sort_order' => 1]);

        $this->get('/')
            ->assertSee('Tentor berpengalaman yang membimbing siswa My Rubel.')
            ->assertSeeInOrder(['id="tentang"', 'id="pengajar"', 'id="lokasi"'], false)
            ->assertSeeInOrder(['Bu Sari', 'Pak Budi'])
            ->assertSee('Alumni Universitas Negeri Padang')
            ->assertSee('“Belajar itu menyenangkan.”', false);
    }

    public function test_harga_dan_satuan_menyatu(): void
    {
        $this->get('/program/sd')->assertSee('Rp1.500.000<small>/semester</small>', false);
        $this->assertStringContainsString('justify-content: flex-start; gap: 4px;', file_get_contents(resource_path('css/app.css')));
    }

    public function test_halaman_admin_testimoni_dan_pengajar_terbuka(): void
    {
        $this->actingAs(User::first());

        foreach (['testimoni', 'testimoni/create', 'pengajar', 'pengajar/create'] as $path) {
            $this->get("/admin/$path")->assertOk();
        }
    }
}
