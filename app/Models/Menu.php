<?php

namespace App\Models;

use App\Services\MultiLanguageService;
use Datlechin\FilamentMenuBuilder\Models\Menu as BaseMenu;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Menu extends BaseMenu
{
    protected function name(): Attribute
    {
        $multiLanguage = app(MultiLanguageService::class);

        return Attribute::make(
            get: function (mixed $value) use ($multiLanguage): array {
                $decoded = json_decode($value, true);

                if (is_array($decoded)) {
                    return $decoded;
                }

                return [$multiLanguage->getDefaultLocale() => $decoded ?? ''];
            },
            set: function (mixed $value) use ($multiLanguage): string {
                if (is_string($value)) {
                    return json_encode([$multiLanguage->getDefaultLocale() => $value]);
                }

                return json_encode($value);
            },
        );
    }
}
