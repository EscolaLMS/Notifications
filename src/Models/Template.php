<?php

namespace EscolaLms\Notifications\Models;

use EscolaLms\Notifications\Database\Factories\TemplateFactory;
use EscolaLms\Notifications\Services\Contracts\TemplateServiceContract;
use EscolaLms\Templates\Models\Template as BaseTemplate;

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

    protected static function booted()
    {
        self::saved(function (Template $template) {
            if ($template->is_default) {
                Template::where($template->getKeyName(), '!=', $template->getKey())
                    ->where([
                        'type' => $template->type,
                        'vars_set' => $template->vars_set,
                    ])->update(['is_default' => false]);
            }
        });
    }

    protected static function newFactory()
    {
        return TemplateFactory::new();
    }

    public function getTitleIsValidAttribute(): bool
    {
        $service = app(TemplateServiceContract::class);
        return $service->titleIsValid($this);
    }
}
