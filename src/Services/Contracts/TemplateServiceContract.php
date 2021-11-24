<?php

namespace EscolaLms\Notifications\Services\Contracts;

use EscolaLms\Templates\Models\Template as BaseTemplate;

interface TemplateServiceContract
{
    public function titleIsValid(BaseTemplate $template): bool;
}
