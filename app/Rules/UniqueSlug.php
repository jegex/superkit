<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueSlug implements ValidationRule
{
    public function __construct(
        private string $modelClass,
        private string $locale,
        private ?string $type = null,
        private ?int $ignoreId = null,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = $this->modelClass::query()
            ->where("slug->{$this->locale}", $value);

        if ($this->type) {
            $query->where('type', $this->type);
        }

        if ($this->ignoreId) {
            $query->whereKeyNot($this->ignoreId);
        }

        if ($query->exists()) {
            $fail("Slug '{$value}' sudah dipakai.");
        }
    }
}
