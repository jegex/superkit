<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class SlugService
{
    public function __construct(
        private readonly MultiLanguageService $multiLanguage,
    ) {}

    public function generate(string $source, string $modelClass, ?string $type, ?Model $record = null): string
    {
        $slug = str($source)->slug()->toString();
        $originalSlug = $slug;
        $count = 1;

        while ($this->isTaken($slug, $modelClass, $type, $record)) {
            $slug = $originalSlug.'-'.$count;
            $count++;
        }

        return $slug;
    }

    public function isTaken(string $slug, string $modelClass, ?string $type, ?Model $record = null): bool
    {
        $locales = $this->multiLanguage->getSupportedLocales();

        /* @var Model $modelClass */
        return $modelClass::query()
            ->when(
                $type,
                fn ($query) => $query->where('type', $type)
            )
            ->when($record, fn ($query) => $query->whereKeyNot($record))
            ->whereJsonContainsLocales('slug', $locales, $slug)
            ->exists();
    }
}
