<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum ContentType: string implements HasLabel
{
    case Post = 'post';
    case Page = 'page';
    case Product = 'product';

    public function getLabel(): string|Htmlable|null
    {
        return str($this->value)->headline();
    }
}
