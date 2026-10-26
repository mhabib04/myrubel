<?php

namespace Tests\Feature;

use App\Filament\Pages\PengaturanMotivation;
use App\Models\Activity;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.admin_password' => 'rahasia-uji']);
        $this->seed(DatabaseSeeder::class);
    }

    public function test_upload_gambar_svg_ditolak(): void
    {
        Storage::fake('public');
        $this->actingAs(User::first());

        $svg = UploadedFile::fake()->createWithContent('hero.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');
        Livewire::test(PengaturanMotivation::class)
            ->set('data.hero_image', [$svg])
            ->call('save')
            ->assertHasErrors();

        Livewire::test(PengaturanMotivation::class)
            ->set('data.hero_image', [UploadedFile::fake()->image('hero.jpg', 1080, 1350)])
            ->call('save')
            ->assertHasNoErrors();
    }

    public function test_skrip_di_isi_event_tidak_ikut_dijalankan(): void
    {
        $event = Activity::create(['title' => 'Event Uji', 'date' => '2026-01-01',
            'content' => '<p>Halo</p><script>alert(1)</script><img src="x" onerror="alert(2)">']);

        $this->get("http://localhost/event/{$event->slug}")
            ->assertOk()
            ->assertSee('<p>Halo</p>', false)
            ->assertDontSee('<script>alert(1)', false)
            ->assertDontSee('onerror', false);
    }

    public function test_admin_biasa_tidak_bisa_membuka_kelola_akun(): void
    {
        $admin = User::create(['name' => 'Admin Uji', 'username' => 'admin-uji', 'password' => 'password-uji', 'role' => 'admin', 'is_active' => true]);

        $this->actingAs($admin)->get('http://localhost/admin/akun')->assertForbidden();

        $admin->update(['is_active' => false]);
        $this->actingAs($admin)->get('http://localhost/admin')->assertForbidden();
    }
}
