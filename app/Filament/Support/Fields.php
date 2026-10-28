<?php

namespace App\Filament\Support;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

/**
 * Komponen yang dipakai bersama oleh semua resource koleksi.
 */
class Fields
{
    /** $ratio: rasio yang disarankan, mis. '4:5 (1080×1350 px)'. */
    public static function image(string $name, string $label, string $directory, ?string $ratio = null): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            // tanpa SVG: file SVG bisa berisi skrip yang ikut berjalan saat dibuka dari website
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            // diperkecil di browser sebelum diunggah (sisi terpanjang maks. 1920 px), agar website ringan
            // dan preview WhatsApp tidak gagal karena file terlalu besar
            ->imageResizeMode('contain')
            ->imageResizeTargetWidth('1920')
            ->imageResizeTargetHeight('1920')
            ->imageResizeUpscale(false)
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->maxSize(4096)
            ->helperText(($ratio ? "Rasio {$ratio}. " : '').'JPG, PNG, atau WebP, maks. 4 MB.');
    }

    public static function imageColumn(string $name, string $label): ImageColumn
    {
        return ImageColumn::make($name)->label($label)->disk('public')->square();
    }

    public static function published(): Toggle
    {
        return Toggle::make('is_published')
            ->label('Tampilkan di website')
            ->default(true)
            ->helperText('Nonaktifkan untuk menyembunyikan tanpa menghapus.');
    }

    public static function publishedColumn(): ToggleColumn
    {
        return ToggleColumn::make('is_published')->label('Tampil');
    }

    /**
     * Tabel koleksi. Tanpa $dateColumn: urut sesuai sort_order dan bisa digeser (drag).
     * Dengan $dateColumn (tanggal/tahun): otomatis urut dari yang terbaru, tanpa drag.
     */
    public static function table(Table $table, array $columns, ?string $dateColumn = null): Table
    {
        $table = $dateColumn
            ? $table->defaultSort(fn ($query) => $query->orderByRaw("{$dateColumn} is null")->orderByDesc($dateColumn)->orderByDesc('id'))
            : $table->reorderable('sort_order')->defaultSort('sort_order');

        return $table
            ->columns([...$columns, static::publishedColumn()])
            ->paginated(false)
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    /** Isi lengkap untuk halaman detail; ringkasan di beranda diambil otomatis dari sini. */
    public static function detailFields(string $directory): array
    {
        return [
            RichEditor::make('content')->label('Isi lengkap')->columnSpanFull()
                ->toolbarButtons([['bold', 'italic', 'underline', 'link'], ['h2', 'h3', 'bulletList', 'orderedList', 'blockquote'], ['undo', 'redo']])
                ->helperText('Beberapa kata pertama tampil sebagai ringkasan di beranda.'),
        ];
    }
}
