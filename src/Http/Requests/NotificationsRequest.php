<?php

namespace EscolaLms\Notifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationsRequest extends FormRequest
{
    public function authorize()
    {
        return !is_null($this->user());
    }

    public function rules()
    {
        return [
            'event' => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function getEvent(): ?string
    {
        return $this->input('event');
    }
}
