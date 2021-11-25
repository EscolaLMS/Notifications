<?php

namespace EscolaLms\Notifications\Core\Traits;

use Illuminate\Support\Str;

trait ContentIsValidIfContainsRequiredVariables
{
    abstract public static function getRequiredVariables(): array;

    public static function isValid(string $content): bool
    {
        return Str::containsAll($content, static::getRequiredVariables());
    }
}
