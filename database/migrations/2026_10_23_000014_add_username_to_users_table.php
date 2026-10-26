<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

// Login panel admin memakai username; email tidak wajib lagi.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
        });

        // username untuk akun yang sudah ada: diambil dari bagian depan email (admin@... -> admin)
        $used = [];
        foreach (DB::table('users')->orderBy('id')->get(['id', 'email']) as $row) {
            $username = Str::slug(Str::before((string) $row->email, '@'), '') ?: 'admin';
            $base = $username;
            for ($i = 2; in_array($username, $used, true); $i++) {
                $username = $base.$i;
            }
            $used[] = $username;
            DB::table('users')->where('id', $row->id)->update(['username' => $username]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->unique()->change();
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
