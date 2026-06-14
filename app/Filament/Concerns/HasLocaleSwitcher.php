<?php

namespace App\Filament\Concerns;

use App\Services\MultiLanguageService;
use LaraZeus\SpatieTranslatable\Actions\LocaleSwitcher;

trait HasLocaleSwitcher
{
    protected function getLocaleSwitcherActions(): array
    {
        if (! app(MultiLanguageService::class)->isEnabled()) {
            return [];
        }

        return [LocaleSwitcher::make()];
    }
}
