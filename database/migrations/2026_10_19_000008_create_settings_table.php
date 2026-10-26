<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Satu baris saja: pengaturan umum situs.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title');
            $table->string('hero_subtitle')->nullable();
            $table->string('hero_tagline')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('about_title')->nullable();
            $table->text('about_body')->nullable();
            $table->string('about_image')->nullable();
            $table->text('quote')->nullable();
            $table->string('quote_author')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
