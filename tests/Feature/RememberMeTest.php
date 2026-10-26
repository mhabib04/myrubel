<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use App\Filament\Auth\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use Tests\TestCase;

class RememberMeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.admin_password' => 'rahasia-uji']);
        $this->seed(DatabaseSeeder::class);
    }

    public function test_ingat_saya_tetap_login_setelah_sesi_habis(): void
    {
        $user = User::first();

        Livewire::test(Login::class)
            ->fillForm(['username' => $user->username, 'password' => 'rahasia-uji', 'remember' => true])
            ->call('authenticate')
            ->assertHasNoFormErrors();

        $user->refresh();
        $this->assertNotNull($user->remember_token, 'remember_token tidak tersimpan');

        // Sesi dibuang (seperti sesi kedaluwarsa); yang tersisa hanya cookie "ingat saya".
        Auth::logout();
        $user->refresh();
        $this->app['auth']->forgetGuards();
        $this->flushSession();

        $recaller = Auth::guard('web')->getRecallerName();
        $value = $user->getAuthIdentifier().'|'.$user->getRememberToken().'|'.$user->getAuthPassword();

        $this->withCookie($recaller, $value)->get('/admin')->assertOk();
    }

    public function test_tanpa_ingat_saya_harus_login_lagi_setelah_sesi_habis(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }
}
