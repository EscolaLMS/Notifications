<?php

namespace EscolaLms\Notifications\Core\Traits;

use Illuminate\Support\Str;

trait TitleIsValidIfContainsRequiredVariables
{
    abstract public static function getRequiredTitleVariables(): array;

    public static function titleIsValid(?string $title): bool
    {
        return !is_null($title) && Str::containsAll($title, self::getRequiredTitleVariables());
    }
}
