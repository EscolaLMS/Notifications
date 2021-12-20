<?php

namespace EscolaLms\Notifications\Services\Contracts;

use EscolaLms\Core\Models\User;
use EscolaLms\Notifications\Dtos\NotificationsFilterCriteriaDto;
use Illuminate\Pagination\LengthAwarePaginator;

interface DatabaseNotificationsServiceContract
{
    public function getUserNotifications(
        User $user,
        NotificationsFilterCriteriaDto $notificationsFilterDto
    ): LengthAwarePaginator;
    public function getAllNotifications(NotificationsFilterCriteriaDto $notificationsFilterDto): LengthAwarePaginator;
    public function getEvents(): array;
}
