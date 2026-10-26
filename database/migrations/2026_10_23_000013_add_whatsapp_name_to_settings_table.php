<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // nama yang tampil di pilihan kontak WhatsApp untuk nomor utama
            $table->string('whatsapp_name')->nullable()->after('whatsapp');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('whatsapp_name');
        });
    }
};
