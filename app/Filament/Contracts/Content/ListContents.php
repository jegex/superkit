<?php

namespace App\Filament\Contracts\Content;

use App\Filament\Concerns\HasLocaleSwitcher;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ListRecords\Concerns\Translatable;

class ListContents extends ListRecords
{
    use HasLocaleSwitcher;
    use Translatable;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getLocaleSwitcherActions(),
            CreateAction::make(),
        ];
    }
}
