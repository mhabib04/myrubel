<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /** Akun baru selalu admin biasa; super admin hanya satu. */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return [...$data, 'role' => 'admin'];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
