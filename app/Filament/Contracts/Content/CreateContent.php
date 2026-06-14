<?php

namespace App\Filament\Contracts\Content;

use App\Filament\Concerns\HasLocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateContent extends CreateRecord
{
    use HasLocaleSwitcher;
    use Translatable;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getLocaleSwitcherActions(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (! isset($data['type'])) {
            $data['type'] = static::getResource()::getType();
        }

        return $data;
    }
}
