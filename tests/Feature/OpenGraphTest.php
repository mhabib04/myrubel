<?php

namespace Tests\Feature;

use App\Models\Work;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OpenGraphTest extends TestCase
{
    use RefreshDatabase;

    public function test_preview_link_menyertakan_ukuran_gambar(): void
    {
        // file asli di disk public: ukurannya dibaca dari storage/app/public
        $path = UploadedFile::fake()->image('poster.jpg', 1600, 900)->store('uji-og', 'public');

        try {
            $work = Work::create(['type' => 'puisi', 'title' => 'Hujan Pagi', 'author' => 'Rina', 'poster' => $path]);

            $this->get("http://literacy.test/karya/puisi/{$work->slug}")
                ->assertOk()
                ->assertSee('<meta property="og:image" content="http://literacy.test/storage/'.$path.'">', false)
                ->assertSee('<meta property="og:image:width" content="1600">', false)
                ->assertSee('<meta property="og:image:height" content="900">', false)
                ->assertSee('<meta property="og:image:type" content="image/jpeg">', false);
        } finally {
            Storage::disk('public')->deleteDirectory('uji-og');
        }
    }
}
