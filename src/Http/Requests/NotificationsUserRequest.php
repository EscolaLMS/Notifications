<?php

namespace EscolaLms\Notifications\Http\Requests;

use EscolaLms\Notifications\Enums\NotificationsPermissionsEnum;
use EscolaLms\Notifications\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationsUserRequest extends FormRequest
{
    public function authorize()
    {
        return !is_null($this->user());
    }

    protected function prepareForValidation()
    {
        if ($this->user()->can(NotificationsPermissionsEnum::READ_ALL_NOTIFICATIONS)) {
            $this->merge(['user' => $this->route('user')]);
        } else {
            $this->merge(['user' => $this->user()->getKey()]);
        }
    }

    public function rules()
    {
        return [
            'user' => ['required', 'integer', Rule::exists('users', 'id')],
            'event' => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function getUser(): User
    {
        return User::find($this->input('user'));
    }

    public function getEvent(): ?string
    {
        return $this->input('event');
    }
}
