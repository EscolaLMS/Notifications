<?php

namespace EscolaLms\Notifications\Repositories;

use EscolaLms\Notifications\Repositories\Contracts\TemplateRepositoryContract;
use EscolaLms\Notifications\Models\Template;
use EscolaLms\Templates\Repository\TemplateRepository as BaseTemplateRepository;

class TemplateRepository extends BaseTemplateRepository implements TemplateRepositoryContract
{
    public function model(): string
    {
        return Template::class;
    }

    public function getFieldsSearchable(): array
    {
        return [
            'type',
            'vars_set',
            'is_default',
        ];
    }

    public function findDefaultForTypeAndSubtype(string $type, string $subtype): ?Template
    {
        return $this->allQuery()
            ->where('type', $type)
            ->where('vars_set', $subtype)
            ->where('is_default', true)
            ->first();
    }
}
