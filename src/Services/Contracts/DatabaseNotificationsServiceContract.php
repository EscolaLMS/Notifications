<?php

namespace EscolaLms\Notifications\Services\Contracts;

use EscolaLms\Core\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface DatabaseNotificationsServiceContract
{
    public function getUserNotifications(User $user, bool $includeRead = false, ?string $event = null): LengthAwarePaginator;
    public function getAllNotifications(bool $includeRead = false, ?string $event = null): LengthAwarePaginator;
    public function getEvents(): array;
}
