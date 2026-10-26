<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

// Kegiatan & Info punya halaman detail ("Lihat Selengkapnya"); kategori Info tidak dipakai lagi.
return new class extends Migration
{
    public function up(): void
    {
        foreach (['activities', 'infos'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('slug')->nullable()->after('title');
                $table->text('summary')->nullable()->after('slug');
                $table->longText('content')->nullable()->after('summary');
            });

            // slug untuk data yang sudah ada
            $used = [];
            foreach (DB::table($tableName)->orderBy('id')->get(['id', 'title']) as $row) {
                $slug = Str::slug($row->title) ?: 'data';
                $base = $slug;
                for ($i = 2; in_array($slug, $used, true); $i++) {
                    $slug = $base.'-'.$i;
                }
                $used[] = $slug;
                DB::table($tableName)->where('id', $row->id)->update(['slug' => $slug]);
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->unique('slug');
            });
        }

        Schema::table('infos', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('infos', function (Blueprint $table) {
            $table->string('category')->default('Pengumuman');
        });

        foreach (['activities', 'infos'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropUnique(['slug']);
                $table->dropColumn(['slug', 'summary', 'content']);
            });
        }
    }
};
