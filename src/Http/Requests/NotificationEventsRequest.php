<?php

namespace EscolaLms\Notifications\Http\Requests;

use EscolaLms\Notifications\Enums\NotificationsPermissionsEnum;
use Illuminate\Foundation\Http\FormRequest;

class NotificationEventsRequest extends FormRequest
{

    public function authorize()
    {
        return $this->user() && $this->user()->can(NotificationsPermissionsEnum::READ_NOTIFICATION_EVENTS_LIST);
    }

    public function rules()
    {
        return [];
    }
}
