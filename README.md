# My Rubel – Website Profil

Satu aplikasi untuk beberapa website **My Rubel (Rumah Belajar), Pasaman Barat**:

- **Bimbel My Rubel** (domain utama, mis. `myrubel.com`): Program, Prestasi, Alumni, Testimoni, Event, Info, Galeri, Tentang, Pengajar, Lokasi.
- **My Rubel Literacy Center** (subdomain, diatur lewat `LITERACY_DOMAIN` di `.env`, mis. `literacy.myrubel.com`): Program, Prestasi, Karya (Puisi, Cerpen, Essay, Artikel), Event, Testimoni.
- **My Rubel Motivation & Outbound Center** (subdomain, diatur lewat `MOTIVATION_DOMAIN`, mis. `motivation.myrubel.com`): Program, Event, Testimoni.
- **Panel admin**: `/admin` (Filament 5) di domain utama, satu panel untuk semua website. Menu dikelompokkan per website; logo, nomor WhatsApp, dan akun admin dipakai bersama.

Di komputer, website Literacy dibuka di `http://literacy.localhost:8000` dan Motivation di `http://motivation.localhost:8000` (nilai bawaan `LITERACY_DOMAIN` dan `MOTIVATION_DOMAIN`).

Stack: Laravel 12, PHP 8.2+, MySQL/MariaDB, Filament 5, Vite.

---

## Menjalankan di komputer (Windows + XAMPP)

Kebutuhan:
- PHP 8.2+ dengan ekstensi `intl`, `zip`, `gd`, `fileinfo`, `mbstring`, `pdo_mysql`
- Composer
- Node.js 20+
- MySQL dari XAMPP dalam keadaan jalan

Langkah:

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
```

Buat database kosong bernama `myrubel` (lewat phpMyAdmin, atau `C:\xampp\mysql\bin\mysql -uroot -e "CREATE DATABASE myrubel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"`).

Buka `.env`, lalu isi:
- `DB_DATABASE=myrubel`, `DB_USERNAME=root`, `DB_PASSWORD=` (bawaan XAMPP)
- `ADMIN_USERNAME` dan `ADMIN_PASSWORD` untuk akun super admin (Bu Lina)

Setelah itu jalankan:

```bash
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Buka:
- Website: http://127.0.0.1:8000
- Admin: http://127.0.0.1:8000/admin (login dengan `ADMIN_USERNAME` dan `ADMIN_PASSWORD`)

Selama mengubah CSS/JS, jalankan `npm run dev` di terminal lain supaya perubahan langsung terlihat tanpa build ulang.

Menjalankan test otomatis:

```bash
php artisan test
```

---

## Deploy ke shared hosting (SSH)

Kebutuhan hosting: PHP 8.2+ dengan ekstensi di atas, MySQL/MariaDB, akses SSH, dan Composer (atau `composer.phar`). Node.js **tidak** dibutuhkan, karena hasil build CSS/JS (`public/build`) ikut diunggah.

### 1. Siapkan di komputer

```bash
npm run build
```

Pastikan folder `public/build` ikut terunggah (folder ini sengaja tidak di-ignore di `.gitignore`).

### 2. Unggah kode

Taruh proyek di **luar** folder publik hosting, misalnya `~/myrubel`. Caranya bisa lewat `git clone`, atau unggah zip (tanpa `vendor/` dan `node_modules/`) lalu ekstrak.

### 3. Pasang dependensi & konfigurasi

```bash
cd ~/myrubel
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

Buat database dan user MySQL dari panel hosting (cPanel → MySQL Databases), lalu isi `.env`:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

DB_DATABASE=nama_database
DB_USERNAME=user_database
DB_PASSWORD=password_database

ADMIN_USERNAME=bulina
ADMIN_PASSWORD=password-yang-kuat
```

### 4. Database, storage, dan cache

```bash
php artisan migrate --force
php artisan db:seed --force      # HANYA sekali, saat pertama kali deploy
php artisan storage:link
php artisan optimize
php artisan filament:optimize
```

> ⚠️ **Jika aplikasi dipasang di subfolder** (misalnya `domain.com/myrubel`), ganti `php artisan optimize` dengan `php artisan config:cache && php artisan view:cache && php artisan event:cache` dan **jangan cache route**. Dengan cache route, Laravel tidak mengenali beranda `/myrubel/` dan membalas error 405.

> ⚠️ **Jangan jalankan `db:seed` lagi setelah website berisi data.** Seeder mengembalikan teks Program, Prestasi, Kegiatan, Cabang, dan Pengaturan ke isi awal, sehingga perubahan dari admin tertimpa.

Kalau `storage:link` gagal karena fungsi `symlink()` dimatikan hosting, buat link lewat SSH:

```bash
ln -s ~/myrubel/storage/app/public ~/myrubel/public/storage
```

### 5. Arahkan domain ke folder `public`

Document root domain **harus** folder `~/myrubel/public`, bukan folder proyek. Kalau salah, file `.env` bisa terbaca orang lain, dan gambar (`/storage/...`) tidak termuat.

- **Addon domain / subdomain (cPanel)**: ubah *Document Root* menjadi `myrubel/public`.
- **Domain utama yang memakai `public_html`**: ganti `public_html` dengan link ke folder `public`:

  ```bash
  mv ~/public_html ~/public_html_lama
  ln -s ~/myrubel/public ~/public_html
  ```

Pastikan folder `storage` dan `bootstrap/cache` bisa ditulis oleh PHP:

```bash
chmod -R 775 storage bootstrap/cache
```

### Update berikutnya

```bash
cd ~/myrubel
git pull                      # atau unggah file yang berubah (termasuk public/build)
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize
php artisan filament:optimize
```

Jika ada error setelah update, bersihkan cache dengan `php artisan optimize:clear`, lalu jalankan `php artisan optimize` lagi.

---

## Catatan pengelolaan

- **Akun admin**: login memakai **username**. Hanya ada satu **super admin** (Bu Lina), yang bisa membuat, mengubah, dan menonaktifkan akun admin di menu *Akun Admin*. Setiap admin bisa mengganti nama, username, dan password sendiri di menu *Profil*. Jika password super admin lupa, jalankan di server: `php artisan tinker --execute="App\Models\User::where('role', 'super_admin')->update(['password' => bcrypt('PASSWORD_BARU')])"`.
- **Sorotan kuning**: di judul hero dan tagline, kata yang diapit tanda bintang tampil dengan sorotan kuning, contoh `Rumah Belajar *Pertama* di Pasaman Barat`.
- **Section kosong**: Program, Prestasi, Alumni, Kegiatan, Info, Galeri, dan Lokasi otomatis disembunyikan (beserta menunya) selama belum ada data yang ditampilkan.
- **Peta cabang**: di Admin → Cabang, kolom *Peta Google Maps* diisi kode dari Google Maps → Bagikan → Sematkan peta → Salin HTML. Sistem hanya menyimpan alamat peta `https://www.google.com/maps/embed?pb=…` dan menolak kode lain. Jika lokasi belum terdaftar di Google, kolom ini boleh diisi koordinat (`0.1234, 99.8765`) atau nama tempat.
- **Nomor WhatsApp**: boleh ditulis `0812…` atau `+62 812…`, otomatis disimpan dalam format `62…` untuk link `wa.me`.
- **Gambar**: tersimpan di `storage/app/public` (dipakai lewat `/storage/...`). File lama otomatis dihapus saat gambar diganti atau datanya dihapus. Sertakan folder ini saat backup, bersama database.
