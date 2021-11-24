<?php

namespace EscolaLms\Notifications\Http\Requests;

use EscolaLms\Templates\Http\Requests\TemplateUpdateRequest as BaseTemplateUpdateRequest;
use EscolaLms\Templates\Models\Template;

class TemplateUpdateRequest extends BaseTemplateUpdateRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'title' => ['sometimes', 'string'],
            'is_default' => ['sometimes', 'boolean'],
        ]);
    }

    public function getTemplate(): ?Template
    {
        return Template::find($this->route('id'));
    }
}
