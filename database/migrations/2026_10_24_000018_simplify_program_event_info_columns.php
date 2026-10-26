<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Judul halaman program = nama program; ringkasan Event & Info dibuat otomatis dari isi lengkap.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('title');
        });

        foreach (['activities', 'infos'] as $tableName) {
            // ringkasan lama dipindah ke isi lengkap jika isinya masih kosong
            foreach (DB::table($tableName)->whereNotNull('summary')->get(['id', 'summary', 'content']) as $row) {
                if (blank(strip_tags((string) $row->content))) {
                    DB::table($tableName)->where('id', $row->id)->update(['content' => '<p>'.e($row->summary).'</p>']);
                }
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('summary');
            });
        }
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->string('title')->default('')->after('slug');
        });

        foreach (['activities', 'infos'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->text('summary')->nullable()->after('slug');
            });
        }
    }
};
