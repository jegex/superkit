<?php

namespace App\Filament\Support;

use App\Services\MultiLanguageService;
use Filament\Forms\Components\Field;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class TranslatableField
{
    public static function wrap(Field $field, array $locales, ?string $primaryLocale = null): Tabs
    {
        $fieldName = $field->getName();
        $primaryLocale ??= $locales[0];

        $tabs = array_map(function (string $locale) use ($field, $primaryLocale, $fieldName): Tab {
            $clonedField = clone $field;
            $clonedField->statePath("{$fieldName}.{$locale}");

            if ($locale !== $primaryLocale) {
                $clonedField->required(false);
            }

            return Tab::make(strtoupper($locale))->schema([$clonedField]);
        }, $locales);

        return Tabs::make($fieldName)->tabs($tabs)->columnSpanFull();
    }

    public static function wrapIfEnabled(Field $field, ?array $locales = null, ?string $primaryLocale = null): Field|Tabs
    {
        $multiLanguage = app(MultiLanguageService::class);

        if (! $multiLanguage->isEnabled()) {
            $locale = app()->getLocale();

            $cloneField = clone $field;
            $cloneField->statePath("{$field->getName()}.$locale");

            return $cloneField;
        }

        $locales ??= $multiLanguage->getSupportedLocales();

        return static::wrap($field, $locales, $primaryLocale);
    }
}
