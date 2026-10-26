<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Activity;
use App\Models\Branch;
use App\Models\Program;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private const FASILITAS = [
        'Menggunakan Kurikulum Merdeka',
        'Modul belajar lengkap dan sistematis',
        'Diajarkan oleh tutor yang berkualitas & berpengalaman di bidangnya',
        'Kelas eksklusif dengan jumlah siswa dibatasi maks. 12 siswa per kelas',
        'Kelas nyaman full AC',
        'Free WiFi sepuasnya',
        'Gedung representatif 2 lantai dengan tempat parkir yang luas & dijaga oleh petugas keamanan',
    ];

    public function run(): void
    {
        // Super admin (Bu Lina): username & password diambil dari .env (ADMIN_USERNAME, ADMIN_PASSWORD)
        User::updateOrCreate(
            ['username' => config('app.admin_username')],
            ['name' => 'Lina Syariyenti', 'role' => 'super_admin', 'is_active' => true, 'password' => config('app.admin_password') ?: throw new \RuntimeException('Isi ADMIN_PASSWORD di .env dulu.')],
        );

        $programs = [
            ['sd', 'Bimbel SD', 'Program untuk siswa SD hingga proses masuk SMP', '3× seminggu, 90 menit/pertemuan', 'Matematika, IPAS, Bahasa Inggris, Bahasa Indonesia', 1500000],
            ['smp', 'Bimbel SMP', 'Program untuk siswa SMP hingga proses masuk SMA', '[Jadwal]', '[Mata pelajaran]', null],
            ['sma', 'Bimbel SMA', 'Program untuk siswa SMA hingga proses masuk kuliah', '[Jadwal]', '[Mata pelajaran]', null],
            ['inggris', 'Bahasa Inggris', 'Program belajar bahasa Inggris', '[Jadwal]', '[Materi]', null],
            ['olimpiade', 'Olimpiade', 'Program untuk persiapan olimpiade', '[Jadwal]', '[Bidang olimpiade]', null],
            ['calistung', 'Calistung', 'Program untuk anak-anak baca tulis hitung', '[Jadwal]', 'Membaca, menulis, berhitung', null],
        ];
        foreach ($programs as $i => [$slug, $name, $desc, $schedule, $subjects, $price]) {
            Program::updateOrCreate(['slug' => $slug], [
                'name' => $name, 'short_desc' => $desc,
                'schedule' => $schedule, 'subjects' => $subjects,
                'price' => $price, 'price_unit' => 'semester',
                'features' => self::FASILITAS, 'sort_order' => $i + 1,
            ]);
        }

        $prestasi = [
            'Juara 3 Olimpiade Madrasah Indonesia, Cabang IPA',
            'Olimpiade IPA & IPS SD/MI Pasaman Barat',
        ];
        foreach ($prestasi as $i => $title) {
            Achievement::updateOrCreate(['title' => $title], [
                'level' => 'Kabupaten', 'student_name' => '[Nama siswa]', 'year' => null, 'sort_order' => $i + 1,
            ]);
        }

        Activity::updateOrCreate(['title' => '3rd My Rubel Competition'], [
            'date' => '2026-02-07', 'place' => 'Aula Kantor Bupati Pasaman Barat', 'sort_order' => 1,
            'content' => '<p>Ajang lomba tahunan My Rubel untuk pelajar se-Pasaman Barat. [Cerita lengkap event: rangkaian acara, jumlah peserta, dan para juara.]</p>',
        ]);

        // Peta sementara (titik tengah area); ganti dengan kode "Sematkan peta" Google setelah lokasi terdaftar.
        $peta = fn (float $lat, float $lng) => "https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d7979!2d{$lng}!3d{$lat}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1760000000000!5m2!1sid!2sid";
        $cabang = [
            ['Simpang Empat', 'Pusat', '[Perumahan …]', $peta(0.0934112, 99.8251131), false],
            ['Jambak', 'Cabang', '[Jambak …]', $peta(0.0470007, 99.817455), false],
            ['Kinali', 'Cabang', null, 'Kinali, Pasaman Barat', true],
        ];
        foreach ($cabang as $i => [$name, $type, $address, $maps, $soon]) {
            Branch::updateOrCreate(['name' => $name], [
                'type' => $type, 'address' => $address, 'maps_query' => $maps,
                'whatsapp' => $soon ? null : '6281234567890', 'is_coming_soon' => $soon, 'sort_order' => $i + 1,
            ]);
        }

        // Teks yang diapit *bintang* ditampilkan dengan sorotan kuning, seperti di desain.
        Setting::current()->update([
            'hero_title' => 'Rumah Belajar *Pertama* di Pasaman Barat',
            'hero_subtitle' => 'Bimbingan belajar TK–SMA, olimpiade & Basic English sejak 2016',
            'hero_tagline' => 'SMART, *MOTIVATED,* AND INSPIRED',
            'whatsapp' => '6281234567890',
            'whatsapp_name' => 'Lina Syariyenti (Pimpinan)',
            'tiktok_url' => null,
            'instagram_url' => null,
            'about_title' => 'Berawal dari rumah, tumbuh bersama ratusan siswa.',
            'about_body' => "My Rubel (Rumah Belajar) berdiri tahun 2016 sebagai bimbel berkonsep rumah pertama di Pasaman Barat. Kami percaya anak belajar paling baik ketika merasa nyaman, diperhatikan, dan termotivasi.\n\nKini My Rubel membina sekitar 160 siswa aktif bersama 15 tentor, dan setiap tahun menggelar My Rubel Competition untuk pelajar se-Pasaman Barat.",
            'quote' => '[Kutipan Bu Lina tentang alasan mendirikan My Rubel.]',
            'quote_author' => 'Lina Syariyenti, S.Pd. · Pimpinan Bimbel My Rubel',
        ]);
    }
}
