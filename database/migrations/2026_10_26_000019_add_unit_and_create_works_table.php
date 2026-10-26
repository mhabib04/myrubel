<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Satu aplikasi untuk beberapa website: data ditandai milik unit mana (bimbel / literacy).
return new class extends Migration
{
    private const TABLES = ['programs', 'achievements', 'activities', 'testimonials', 'settings'];

    public function up(): void
    {
        foreach (self::TABLES as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('unit')->default('bimbel')->after('id')->index();
            });
        }

        // Karya Literacy Center: puisi, cerpen, essay, artikel
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author')->nullable();
            $table->date('date')->nullable();
            $table->string('poster')->nullable();
            $table->longText('content')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('works');

        foreach (self::TABLES as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropIndex(['unit']);
                $table->dropColumn('unit');
            });
        }
    }
};
