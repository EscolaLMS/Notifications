<?php

namespace EscolaLms\Notifications\Http\Requests;

use EscolaLms\Notifications\Enums\NotificationsPermissionsEnum;
use EscolaLms\Notifications\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationsAdminRequest extends FormRequest
{

    public function authorize()
    {
        return !is_null($this->user()) && $this->user()->can(NotificationsPermissionsEnum::READ_ALL_NOTIFICATIONS);
    }

    protected function prepareForValidation()
    {
        $this->merge(['user' => $this->route('user')]);
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
