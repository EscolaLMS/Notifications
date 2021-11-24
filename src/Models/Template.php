<?php

namespace EscolaLms\Notifications\Models;

use EscolaLms\Templates\Models\Template as BaseTemplate;
use EscolaLms\Templates\Services\Contracts\TemplateServiceContract;

class Template extends BaseTemplate
{
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'type' => 'string',
        'vars_set' => 'string',
        'title' => 'string',
        'content' => 'string',
        'is_default' => 'boolean',
        'mail_theme' => 'string',
        'mail_markdown' => 'string',
    ];

    public $fillable = [
        'name',
        'type',
        'vars_set',
        'title',
        'content',
        'is_default',
        'mail_theme',
        'mail_markdown',
    ];

    public function getTitleIsValidAttribute(): bool
    {
        $service = app(TemplateServiceContract::class);
        return $service->isValid($this);
    }
}
