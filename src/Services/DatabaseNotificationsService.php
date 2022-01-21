<?php

namespace EscolaLms\Notifications\Services;

use EscolaLms\Core\Models\User;
use EscolaLms\Core\Repositories\Criteria\Criterion;
use EscolaLms\Notifications\Models\DatabaseNotification;
use EscolaLms\Notifications\Models\User as NotificationsUser;
use EscolaLms\Notifications\Services\Contracts\DatabaseNotificationsServiceContract;
use EscolaLms\Notifications\Dtos\NotificationsFilterCriteriaDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class DatabaseNotificationsService implements DatabaseNotificationsServiceContract
{
    public function getUserNotifications(
        User $user,
        NotificationsFilterCriteriaDto $notificationsFilterDto
    ): LengthAwarePaginator {
        $user = $user instanceof NotificationsUser ? $user : NotificationsUser::find($user->getKey());

        $query = $user->notifications()
            ->whereNotIn('event', config('escolalms_notifications.except_events'))->getQuery();
        $query = $this->applyCriteria($query, $notificationsFilterDto->toArray());

        return $query->paginate();
    }

    public function getAllNotifications(NotificationsFilterCriteriaDto $notificationsFilterDto): LengthAwarePaginator {
        $query = DatabaseNotification::query();
        $query = $this->applyCriteria($query, $notificationsFilterDto->toArray());

        return $query->paginate();
    }

    public function getEvents(): array
    {
        return DatabaseNotification::select('event')->distinct()->pluck('event')->toArray();
    }

    private function applyCriteria(Builder $query, array $criteria): Builder
    {
        foreach ($criteria as $criterion) {
            if ($criterion instanceof Criterion) {
                $query = $criterion->apply($query);
            }
        }

        return $query;
    }
}
