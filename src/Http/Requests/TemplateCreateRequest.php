<?php

namespace EscolaLms\Notifications\Http\Requests;

use EscolaLms\Templates\Http\Requests\TemplateCreateRequest as BaseTemplateCreateRequest;

class TemplateCreateRequest extends BaseTemplateCreateRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'title' => ['sometimes', 'string'],
            'is_default' => ['sometimes', 'boolean'],
        ]);
    }
}
