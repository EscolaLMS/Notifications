<?php

namespace EscolaLms\Notifications\Core;

use EscolaLms\Templates\Enum\Contracts\TemplateVariableContract;

interface NotificationVariableContract extends TemplateVariableContract
{
    public static function titleIsValid(?string $title): bool;
    public static function getRequiredTitleVariables(): array;

    /**
     * Required method from \EscolaLms\Core\Enums\BasicEnum
     *
     * @param  string|string[]|null $keys
     * @return array
     */
    public static function getValues($keys = null): array;
}
