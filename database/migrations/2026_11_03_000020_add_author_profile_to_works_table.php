<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Profil penulis karya: foto, tempat & tanggal lahir, asal sekolah (kartu di bawah isi karya).
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('works', function (Blueprint $table) {
            $table->string('author_photo')->nullable()->after('author');
            $table->string('author_birthplace')->nullable()->after('author_photo');
            $table->date('author_birthdate')->nullable()->after('author_birthplace');
            $table->string('author_school')->nullable()->after('author_birthdate');
        });
    }

    public function down(): void
    {
        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn(['author_photo', 'author_birthplace', 'author_birthdate', 'author_school']);
        });
    }
};
