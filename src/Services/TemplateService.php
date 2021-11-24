<?php

namespace EscolaLms\Notifications\Services;

use EscolaLms\Notifications\Core\NotificationVariableContract;
use EscolaLms\Notifications\Models\Template;
use EscolaLms\Notifications\Repositories\Contracts\TemplateRepositoryContract;
use EscolaLms\Notifications\Services\Contracts\TemplateServiceContract;
use EscolaLms\Templates\Models\Template as BaseTemplate;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use EscolaLms\Templates\Services\TemplateService as BaseTemplateService;

class TemplateService extends BaseTemplateService implements TemplateServiceContract
{
    protected TemplateRepositoryContract $repository;
    protected VariablesServiceContract $variableService;

    public function __construct(TemplateRepositoryContract $repository, VariablesServiceContract $variableService)
    {
        $this->repository = $repository;
        $this->variableService = $variableService;
        parent::__construct($repository, $variableService);
    }

    public function titleIsValid(BaseTemplate $template): bool
    {
        $enum = $this->variableService->getVariableEnumClassName($template->type, $template->vars_set);
        if (is_a($enum, NotificationVariableContract::class, true)) {
            /** @var NotificationVariableContract $enum */
            return $enum::titleIsValid($template->title);
        }
        return true;
    }

    public function insert(array $data): Template
    {
        /** @var Template $template */
        $template = new Template();
        $template->fill($data);
        $this->repository->insert($template);

        return $template;
    }
}
