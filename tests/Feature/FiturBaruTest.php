<?php

namespace Tests\Feature;

use App\Filament\Resources\ActivityResource\Pages\CreateActivity;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\Achievement;
use App\Models\Activity;
use App\Models\Alumnus;
use App\Models\Branch;
use App\Models\Info;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FiturBaruTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.admin_password' => 'rahasia-uji']);
        $this->seed(DatabaseSeeder::class);
    }

    public function test_koleksi_bertanggal_urut_dari_terbaru_dan_kosong_di_akhir(): void
    {
        Alumnus::create(['name' => 'Alumni Lama', 'graduation_year' => 2019]);
        Alumnus::create(['name' => 'Alumni Tanpa Tahun']);
        Alumnus::create(['name' => 'Alumni Baru', 'graduation_year' => 2025]);

        $this->assertSame(['Alumni Baru', 'Alumni Lama', 'Alumni Tanpa Tahun'], Alumnus::published()->pluck('name')->all());

        Achievement::create(['title' => 'Juara Terbaru', 'level' => 'Provinsi', 'year' => 2026]);
        $this->get('/')->assertSeeInOrder(['Juara Terbaru', 'Juara 3 Olimpiade Madrasah Indonesia']);
    }

    public function test_alumni_tampil_sebagai_kartu_profil(): void
    {
        Alumnus::create(['name' => 'Rina', 'graduation_year' => 2024, 'campus' => 'Universitas Andalas', 'major' => 'Kedokteran']);

        $this->get('/')
            ->assertSee('class="alum"', false)
            ->assertSee('Alumni 2024')
            ->assertSee('Universitas Andalas')
            ->assertSee('Jejak alumni My Rubel di kampus pilihan mereka.');
    }

    public function test_detail_event_dan_info(): void
    {
        $this->get('/event/3rd-my-rubel-competition')
            ->assertOk()
            ->assertSee('<title>3rd My Rubel Competition – Bimbel My Rubel Pasaman Barat</title>', false)
            ->assertSee('7 Februari 2026')
            ->assertSee('Aula Kantor Bupati Pasaman Barat')
            ->assertSee('[Cerita lengkap event', false)
            ->assertSee('data-share', false)
            ->assertSee('Kembali ke Event');

        $info = Info::create(['title' => 'Pendaftaran Semester Genap', 'date' => '2026-12-01',
            'content' => '<p>Dibuka mulai Desember. Isi <strong>lengkap</strong></p><script>alert(1)</script>']);
        $this->assertSame('Dibuka mulai Desember. Isi lengkap', $info->excerpt());
        $this->assertSame('pendaftaran-semester-genap', $info->slug);

        $this->get('/')->assertSee('/info/pendaftaran-semester-genap', false)->assertSee('Dibuka mulai Desember.');
        $this->get('/info/pendaftaran-semester-genap')
            ->assertOk()
            ->assertSee('<strong>lengkap</strong>', false)
            ->assertDontSee('<script>alert(1)</script>', false);

        $info->update(['is_published' => false]);
        $this->get('/info/pendaftaran-semester-genap')->assertNotFound();
    }

    public function test_slug_event_dibuat_otomatis_dan_unik(): void
    {
        $this->actingAs(User::first());

        Livewire::test(CreateActivity::class)
            ->fillForm(['title' => '3rd My Rubel Competition'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertSame(['3rd-my-rubel-competition', '3rd-my-rubel-competition-2'], Activity::orderBy('id')->pluck('slug')->all());
    }

    public function test_info_terbaru_dibatasi_enam_di_beranda(): void
    {
        foreach (range(1, 8) as $i) {
            Info::create(['title' => "Info Nomor $i", 'date' => sprintf('2026-01-%02d', $i)]);
        }

        $this->get('/')
            ->assertSeeInOrder(['Info Nomor 8', 'Info Nomor 3'])
            ->assertDontSee('Info Nomor 2')
            ->assertDontSee('Info Nomor 1<', false);
    }

    public function test_pilihan_kontak_wa_muncul_jika_lebih_dari_satu_nomor(): void
    {
        // seeder: nomor utama & semua cabang sama -> cukup satu kontak, tanpa pilihan
        $this->get('/')->assertDontSee('id="wa-pick"', false);

        Setting::current()->update(['whatsapp' => '081111111111', 'whatsapp_name' => 'Bu Lina (Pimpinan)']);
        Branch::where('name', 'Simpang Empat')->update(['whatsapp' => '6282222222222']);
        Branch::where('name', 'Jambak')->update(['whatsapp' => '6283333333333']);

        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringContainsString('id="wa-pick"', $html);
        $this->assertStringContainsString('Bu Lina (Pimpinan)', $html);
        $this->assertStringContainsString('data-number="6281111111111"', $html);
        $this->assertStringContainsString('Admin Simpang Empat', $html);
        $this->assertStringContainsString('Admin Jambak', $html);
        $this->assertStringNotContainsString('Admin Kinali', $html); // segera hadir
        $this->assertSame(3, substr_count($html, 'class="wa-pick-item"'));
    }

    public function test_hanya_super_admin_bisa_kelola_akun(): void
    {
        $super = User::first();
        $this->assertTrue($super->isSuperAdmin());

        $this->actingAs($super);
        Livewire::test(CreateUser::class)
            ->fillForm(['name' => 'Admin Jambak', 'username' => 'adminjambak', 'password' => 'password-admin', 'is_active' => true])
            ->call('create')
            ->assertHasNoFormErrors();

        $admin = User::where('username', 'adminjambak')->first();
        $this->assertSame('admin', $admin->role);

        // admin biasa bisa membuka panel, tapi tidak bisa membuka halaman akun
        $this->actingAs($admin);
        $this->get('/admin/event')->assertOk();
        $this->get('/admin/akun')->assertForbidden();
    }

    public function test_login_memakai_username(): void
    {
        auth()->logout();

        Livewire::test(\App\Filament\Auth\Login::class)
            ->fillForm(['username' => 'admin', 'password' => 'salah'])
            ->call('authenticate')
            ->assertHasFormErrors(['username']);

        Livewire::test(\App\Filament\Auth\Login::class)
            ->fillForm(['username' => 'admin', 'password' => 'rahasia-uji'])
            ->call('authenticate')
            ->assertHasNoFormErrors();

        $this->assertAuthenticatedAs(User::where('username', 'admin')->first());
    }

    public function test_super_admin_hanya_satu_dan_tidak_tampil_di_daftar_akun(): void
    {
        $this->actingAs(User::first());

        Livewire::test(CreateUser::class)
            ->fillForm(['name' => 'Coba', 'username' => 'coba', 'password' => 'password-admin', 'role' => 'super_admin'])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertSame('admin', User::where('username', 'coba')->value('role'));
        $this->assertSame(1, User::where('role', 'super_admin')->count());

        Livewire::test(ListUsers::class)
            ->assertCanSeeTableRecords(User::where('username', 'coba')->get())
            ->assertCanNotSeeTableRecords(User::where('role', 'super_admin')->get());
    }

    public function test_profil_bisa_ganti_username_dengan_password_saat_ini(): void
    {
        $this->actingAs(User::first());

        Livewire::test(\App\Filament\Auth\EditProfile::class)
            ->fillForm(['username' => 'bulina', 'currentPassword' => 'rahasia-uji'])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('bulina', User::first()->username);
    }

    public function test_akun_nonaktif_tidak_bisa_masuk_panel(): void
    {
        $admin = User::create(['name' => 'Mantan Admin', 'username' => 'mantan', 'password' => 'password-admin', 'role' => 'admin']);

        $this->actingAs(User::first());
        Livewire::test(ListUsers::class)->call('updateTableColumnState', 'is_active', (string) $admin->getKey(), false);
        $this->assertFalse($admin->refresh()->is_active);

        $this->actingAs($admin);
        $this->get('/admin')->assertForbidden();
        $this->assertFalse($admin->canAccessPanel(Filament::getPanel('admin')));
    }
}
