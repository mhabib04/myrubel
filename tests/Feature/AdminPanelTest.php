<?php

namespace Tests\Feature;

use App\Filament\Pages\Pengaturan;
use App\Filament\Resources\BranchResource\Pages\CreateBranch;
use App\Filament\Resources\BranchResource\Pages\ListBranches;
use App\Filament\Resources\GalleryResource\Pages\CreateGallery;
use App\Filament\Resources\GalleryResource\Pages\EditGallery;
use App\Filament\Resources\ProgramResource\Pages\CreateProgram;
use App\Filament\Resources\ProgramResource\Pages\EditProgram;
use App\Models\Branch;
use App\Models\Gallery;
use App\Models\Program;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Repeater;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.admin_password' => 'rahasia-uji']);
        $this->seed(DatabaseSeeder::class);
        $this->actingAs(User::first());
    }

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        auth()->logout();

        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_semua_halaman_admin_terbuka(): void
    {
        foreach (['program', 'prestasi', 'alumni', 'event', 'testimoni', 'pengajar', 'info', 'galeri', 'cabang', 'pengaturan', 'program/sd/edit'] as $path) {
            $this->get("/admin/$path")->assertOk();
        }
    }

    public function test_edit_program_menyimpan_harga_dan_fasilitas(): void
    {
        $program = Program::where('slug', 'smp')->first();
        Repeater::fake(); // kunci item repeater jadi berurutan agar bisa diisi di test

        Livewire::test(EditProgram::class, ['record' => $program->getRouteKey()])
            ->fillForm(['price' => 1750000, 'features' => [['item' => 'Poin A'], ['item' => 'Poin B']]])
            ->call('save')
            ->assertHasNoFormErrors();

        $program->refresh();
        $this->assertSame(1750000, $program->price);
        $this->assertSame(['Poin A', 'Poin B'], $program->features);
    }

    public function test_program_baru_diberi_urutan_terakhir(): void
    {
        Livewire::test(CreateProgram::class)
            ->fillForm([
                'name' => 'Kelas Uji', 'slug' => 'kelas-uji', 'title' => 'Kelas Uji',
                'short_desc' => 'Deskripsi uji', 'price_unit' => 'bulan',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertSame(7, Program::where('slug', 'kelas-uji')->value('sort_order'));
    }

    public function test_upload_dan_ganti_foto_galeri_menghapus_file_lama(): void
    {
        Storage::fake('public');

        Livewire::test(CreateGallery::class)
            ->fillForm(['image' => UploadedFile::fake()->image('kelas.jpg', 1600, 700), 'caption' => 'Suasana kelas'])
            ->call('create')
            ->assertHasNoFormErrors();

        $gallery = Gallery::first();
        $old = $gallery->image;
        Storage::disk('public')->assertExists($old);
        $this->assertStringStartsWith('galeri/', $old);

        Livewire::test(EditGallery::class, ['record' => $gallery->getRouteKey()])
            ->fillForm(['image' => []]) // seperti admin menekan tombol hapus gambar lama
            ->fillForm(['image' => UploadedFile::fake()->image('baru.jpg', 1600, 700)])
            ->call('save')
            ->assertHasNoFormErrors();

        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($gallery->refresh()->image);
    }

    public function test_tambah_dan_hapus_cabang_dengan_nomor_wa_dinormalisasi(): void
    {
        Livewire::test(CreateBranch::class)
            ->fillForm(['name' => 'Talu', 'type' => 'Cabang', 'whatsapp' => '0812-3456-7890'])
            ->call('create')
            ->assertHasNoFormErrors();

        $branch = Branch::where('name', 'Talu')->first();
        $this->assertSame('6281234567890', $branch->whatsapp);

        Livewire::test(ListBranches::class)
            ->callTableAction(DeleteAction::class, $branch);

        $this->assertModelMissing($branch);
    }

    public function test_upload_logo_di_pengaturan_dan_jadi_favicon_admin(): void
    {
        Storage::fake('public');

        Livewire::test(Pengaturan::class)
            ->set('data.logo', [UploadedFile::fake()->image('logo.png', 256, 256)])
            ->call('save')
            ->assertHasNoErrors();

        $logo = Setting::current()->logo;
        $this->assertStringStartsWith('pengaturan/', $logo);
        Storage::disk('public')->assertExists($logo);

        $this->get('/admin')->assertSee('href="/storage/'.$logo.'"', false);
    }

    public function test_kode_sematkan_peta_disimpan_sebagai_alamat_peta_saja(): void
    {
        $src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989!2d99.82!3d0.09!4m2!1sBimbel+My+Rubel!5e0!3m2!1sid!2sid!4v1';

        Livewire::test(CreateBranch::class)
            ->fillForm(['name' => 'Uji Peta', 'type' => 'Cabang',
                'maps_query' => '<iframe src="'.$src.'" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'])
            ->call('create')
            ->assertHasNoFormErrors();

        $branch = Branch::where('name', 'Uji Peta')->first();
        $this->assertSame($src, $branch->maps_query);
        $this->assertSame($src, $branch->mapEmbedUrl());
    }

    public function test_kode_peta_selain_google_ditolak(): void
    {
        Livewire::test(CreateBranch::class)
            ->fillForm(['name' => 'Uji Peta', 'type' => 'Cabang', 'maps_query' => '<iframe src="https://contoh.com/peta"></iframe>'])
            ->call('create')
            ->assertHasFormErrors(['maps_query']);

        $this->assertSame('0.1234, 99.8765', (new Branch(['maps_query' => ' 0.1234, 99.8765 ']))->maps_query);
        $this->assertStringContainsString('q=0.1234%2C%2099.8765', (new Branch(['maps_query' => '0.1234, 99.8765']))->mapEmbedUrl());
    }

    public function test_pengaturan_tersimpan(): void
    {
        Livewire::test(Pengaturan::class)
            ->assertSet('data.hero_title', 'Rumah Belajar *Pertama* di Pasaman Barat')
            ->set('data.whatsapp', '+62 811-1111-2222')
            ->set('data.instagram_url', 'https://www.instagram.com/myrubel')
            ->call('save')
            ->assertHasNoErrors();

        $setting = Setting::current();
        $this->assertSame('6281111112222', $setting->whatsapp);
        $this->assertSame('https://www.instagram.com/myrubel', $setting->instagram_url);
        $this->assertSame(1, Setting::where('unit', 'bimbel')->count());
    }
}
