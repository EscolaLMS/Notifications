<?php

namespace EscolaLms\Notifications\Core;

use EscolaLms\Notifications\Core\Traits\TitleIsValidIfContainsRequiredVariables;
use EscolaLms\Templates\Enum\EmptyVariableSet;

class NotificationEmptyVariableSet extends EmptyVariableSet implements NotificationVariableContract
{
    use TitleIsValidIfContainsRequiredVariables;

    public static function getRequiredTitleVariables(): array
    {
        return [];
    }
}
