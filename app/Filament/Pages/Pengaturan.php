<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * Satu halaman untuk teks hero, kontak, media sosial, dan bagian Tentang.
 *
 * @property-read Schema $form
 */
class Pengaturan extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $title = 'Pengaturan';

    protected static ?int $navigationSort = 10;

    /** @var array<string, mixed> | null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Setting::current()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        $highlight = 'Apit kata dengan *bintang* untuk sorotan kuning, mis. Rumah Belajar *Pertama*.';

        return $schema
            ->statePath('data')
            ->model(Setting::current())
            ->components([
                Section::make('Logo')->schema([
                    Fields::image('logo', 'Logo', 'pengaturan')
                        ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                        ->maxSize(1024)
                        ->helperText('Tampil di header dan ikon tab browser. Disarankan PNG persegi berlatar transparan, maks. 1 MB. Jika kosong, tampil ikon "MR".'),
                ]),
                Section::make('Hero (bagian paling atas)')->columns(2)->schema([
                    TextInput::make('hero_title')->label('Judul')->required()->maxLength(255)
                        ->helperText($highlight)->columnSpanFull(),
                    TextInput::make('hero_subtitle')->label('Sub judul')->maxLength(255)->columnSpanFull(),
                    TextInput::make('hero_tagline')->label('Tagline')->maxLength(255)
                        ->helperText('Mendukung *bintang* untuk sorotan kuning.')->columnSpanFull(),
                    Fields::image('hero_image', 'Gambar hero', 'pengaturan', '4:5 (1080×1350 px)')->columnSpanFull(),
                ]),
                Section::make('Kontak & media sosial')->columns(2)->schema([
                    TextInput::make('whatsapp')->label('Nomor WhatsApp utama')->tel()->maxLength(20)
                        ->helperText('Nomor utama untuk semua tombol WhatsApp di website.'),
                    TextInput::make('whatsapp_name')->label('Nama kontak utama')->maxLength(100)
                        ->helperText('Nama nomor utama di pilihan kontak WhatsApp.'),
                    TextInput::make('tiktok_url')->label('Link TikTok')->url()->maxLength(255),
                    TextInput::make('instagram_url')->label('Link Instagram')->url()->maxLength(255),
                ]),
                Section::make('Tentang')->schema([
                    TextInput::make('about_title')->label('Judul')->maxLength(255),
                    Textarea::make('about_body')->label('Isi')->rows(6)
                        ->helperText('Pisahkan antarparagraf dengan satu baris kosong.'),
                    Fields::image('about_image', 'Foto', 'pengaturan', '4:5 (1080×1350 px)'),
                    Textarea::make('quote')->label('Kutipan')->rows(3),
                    TextInput::make('quote_author')->label('Nama pengutip')->maxLength(255),
                ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')->label('Simpan')->submit('save')->keyBindings(['mod+s']),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::current()->update($data);

        Notification::make()->success()->title('Pengaturan disimpan')->send();
    }
}
