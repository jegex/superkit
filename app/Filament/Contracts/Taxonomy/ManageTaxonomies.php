<?php

namespace App\Filament\Contracts\Taxonomy;

use App\Filament\Concerns\HasLocaleSwitcher;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use LaraZeus\SpatieTranslatable\Resources\Pages\ManageRecords\Concerns\Translatable;

class ManageTaxonomies extends ManageRecords
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
