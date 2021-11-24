<?php

namespace EscolaLms\Notifications\Repositories\Contracts;

use EscolaLms\Notifications\Models\Template;
use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract as BaseTemplateRepositoryContract;

interface TemplateRepositoryContract extends BaseTemplateRepositoryContract
{
    public function findDefaultForTypeAndSubtype(string $type, string $subtype): ?Template;
}
