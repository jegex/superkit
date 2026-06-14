<?php

namespace App\Filament\Concerns;

use BackedEnum;

trait HasType
{
    protected static string|BackedEnum|null $type = null;

    public static function getType(): string|BackedEnum|null
    {
        if (static::$type instanceof BackedEnum) {
            return static::$type->value;
        }

        return static::$type;
    }
}
