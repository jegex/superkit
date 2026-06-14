<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum TaxonomyType: string implements HasLabel
{
    case Category = 'category';
    case Tag = 'tag';

    public function getLabel(): string|Htmlable|null
    {
        return str($this->value)->headline();
    }
}
