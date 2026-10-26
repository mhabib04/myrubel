<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_404_punya_tombol_ke_beranda_website_yang_dibuka(): void
    {
        $main = rtrim(config('app.url'), '/').'/';

        $this->get('http://localhost/tidak-ada')
            ->assertNotFound()
            ->assertSee('Halaman tidak ditemukan')
            ->assertSee('<a class="btn btn-navy" href="'.$main.'">', false)
            ->assertSee('Kembali ke Bimbel My Rubel');

        $this->get('http://literacy.test/tidak-ada')
            ->assertNotFound()
            ->assertSee('href="http://literacy.test"', false)
            ->assertSee('Kembali ke My Rubel Literacy Center');

        $this->get('http://motivation.test/event/tidak-ada')
            ->assertNotFound()
            ->assertSee('href="http://motivation.test"', false)
            ->assertSee('Kembali ke My Rubel Motivation &amp; Outbound Center', false);
    }
}
