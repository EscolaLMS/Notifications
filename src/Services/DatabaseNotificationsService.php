<?php

namespace EscolaLms\Notifications\Services;

use EscolaLms\Core\Models\User;
use EscolaLms\Notifications\Models\DatabaseNotification;
use EscolaLms\Notifications\Models\User as NotificationsUser;
use EscolaLms\Notifications\Services\Contracts\DatabaseNotificationsServiceContract;
use Illuminate\Pagination\LengthAwarePaginator;

class DatabaseNotificationsService implements DatabaseNotificationsServiceContract
{
    public function getUserNotifications(User $user, bool $includeRead = false, ?string $event = null): LengthAwarePaginator
    {
        $user = $user instanceof NotificationsUser ? $user : NotificationsUser::find($user->getKey());

        $query = $user->notifications();
        if ($event) {
            $query = $query->where('event', $event);
        }
        if (!$includeRead) {
            $query = $query->whereNull('read_at');
        }
        return $query->paginate();
    }

    public function getAllNotifications(bool $includeRead = false, ?string $event = null): LengthAwarePaginator
    {
        $query = DatabaseNotification::query();
        if ($event) {
            $query = $query->where('event', $event);
        }
        if (!$includeRead) {
            $query = $query->whereNull('read_at');
        }
        return $query->paginate();
    }

    public function getEvents(): array
    {
        return DatabaseNotification::select('event')->distinct()->pluck('event')->toArray();
    }
}
