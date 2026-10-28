<?php

namespace Tests\Feature;

use App\Models\Setting;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.admin_password' => 'rahasia-uji']); // domain uji: LITERACY_DOMAIN & MOTIVATION_DOMAIN di phpunit.xml
        $this->seed(DatabaseSeeder::class);
    }

    public function test_production_mengarahkan_http_dan_www_ke_https_tanpa_www(): void
    {
        $this->app['env'] = 'production';

        $this->get('http://localhost/program/sd')->assertRedirect('https://localhost/program/sd')->assertStatus(301);
        $this->get('https://www.literacy.test/karya/puisi?page=2')->assertRedirect('https://literacy.test/karya/puisi?page=2');
        $this->get('https://literacy.test/')->assertOk()->assertSee('My Rubel Literacy Center');
    }

    public function test_di_luar_production_tidak_dialihkan(): void
    {
        $this->get('http://localhost/')->assertOk();
    }

    public function test_tag_canonical_tanpa_query_kecuali_nomor_halaman(): void
    {
        $root = rtrim(config('app.url'), '/'); // alamat pendek di test memakai domain APP_URL

        $this->get('/program/sd?utm_source=wa')
            ->assertSee("<link rel=\"canonical\" href=\"$root/program/sd\">", false)
            ->assertSee("<meta property=\"og:url\" content=\"$root/program/sd\">", false);
        $this->get('http://literacy.test/karya/puisi?page=2&utm_source=wa')
            ->assertSee('<link rel="canonical" href="http://literacy.test/karya/puisi?page=2">', false);
    }

    public function test_data_terstruktur_hanya_di_beranda(): void
    {
        Setting::current()->update(['logo' => 'pengaturan/logo.png', 'instagram_url' => 'https://instagram.com/myrubel']);

        $this->get('/')
            ->assertSee('<script type="application/ld+json">', false)
            ->assertSee('"@type":"EducationalOrganization","name":"Bimbel My Rubel"', false)
            ->assertSee('"telephone":"+6281234567890"', false)
            ->assertSee('"@type":"PostalAddress"', false)
            ->assertSee('"sameAs":["https://instagram.com/myrubel"]', false)
            ->assertSee('/storage/pengaturan/logo.png"', false);

        $this->get('http://motivation.test/')->assertSee('"name":"My Rubel Motivation & Outbound Center"', false);
        $this->get('/program/sd')->assertDontSee('application/ld+json', false);
    }
}
