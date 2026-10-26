<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Pengaturan website Literacy Center: logo, hero & media sosial. WhatsApp
 * memakai Pengaturan My Rubel; logo juga, bila dikosongkan.
 *
 * @property-read Schema $form
 */
class PengaturanLiteracy extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $title = 'Pengaturan Literacy Center';

    protected static ?string $slug = 'literacy/pengaturan';

    protected static ?int $navigationSort = 6;

    protected static string|UnitEnum|null $navigationGroup = 'Literacy Center';

    /** @var array<string, mixed> | null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Setting::for('literacy')->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->model(Setting::for('literacy'))
            ->components([
                Section::make('Logo')->schema([
                    Fields::image('logo', 'Logo', 'pengaturan')
                        ->maxSize(1024)
                        ->helperText('Tampil di header dan ikon tab browser. Disarankan PNG persegi berlatar transparan, maks. 1 MB. Kosongkan untuk memakai logo My Rubel.'),
                ]),
                Section::make('Hero (bagian paling atas)')->columns(2)->schema([
                    TextInput::make('hero_title')->label('Judul')->required()->maxLength(255)->columnSpanFull()
                        ->helperText('Apit kata dengan *bintang* untuk sorotan kuning, mis. My Rubel *Literacy* Center.'),
                    TextInput::make('hero_subtitle')->label('Sub judul')->maxLength(255)->columnSpanFull(),
                    TextInput::make('hero_tagline')->label('Tagline')->maxLength(255)->columnSpanFull()
                        ->helperText('Mendukung *bintang* untuk sorotan kuning.'),
                    Fields::image('hero_image', 'Gambar hero', 'pengaturan', '4:5 (1080×1350 px)')->columnSpanFull(),
                ]),
                Section::make('Media sosial')->columns(2)
                    ->description('Nomor WhatsApp memakai Pengaturan My Rubel.')
                    ->schema([
                        TextInput::make('tiktok_url')->label('Link TikTok')->url()->maxLength(255)
                            ->helperText('Kosongkan untuk memakai link My Rubel.'),
                        TextInput::make('instagram_url')->label('Link Instagram')->url()->maxLength(255)
                            ->helperText('Kosongkan untuk memakai link My Rubel.'),
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
        Setting::for('literacy')->update($this->form->getState());

        Notification::make()->success()->title('Pengaturan disimpan')->send();
    }
}
