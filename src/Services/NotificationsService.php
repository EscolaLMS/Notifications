<?php

namespace EscolaLms\Notifications\Services;

use EscolaLms\Notifications\Core\NotificationContract;
use EscolaLms\Notifications\Models\Template;
use EscolaLms\Notifications\Repositories\Contracts\TemplateRepositoryContract;
use EscolaLms\Notifications\Services\Contracts\NotificationsServiceContract;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

class NotificationsService implements NotificationsServiceContract
{
    private array $notifications;

    private TemplateRepositoryContract $templateRepository;
    private VariablesServiceContract $variablesService;

    public function __construct(TemplateRepositoryContract $templateRepository, VariablesServiceContract $variablesService)
    {
        $this->templateRepository = $templateRepository;
        $this->variablesService = $variablesService;
    }

    public function registerNotification(string $notificationClass): void
    {
        /** @var NotificationContract $notificationClass */
        if (!is_a($notificationClass, NotificationContract::class, true)) {
            throw new InvalidArgumentException("Notification must implement Notification Contract");
        }

        $this->notifications[] = $notificationClass;
        foreach ($notificationClass::availableVia() as $notificationRoute) {
            $this->variablesService::addToken($notificationClass::templateVariablesClass(), $notificationRoute, $notificationClass::templateVariablesSetName());
        }
    }

    public function findTemplateForNotification(NotificationContract $notification, ?string $channel = 'mail'): ?Template
    {
        if (is_null($channel) || !in_array($channel, $notification::availableVia())) {
            return null;
        }
        return $this->templateRepository->findDefaultForTypeAndSubtype($channel, $notification::templateVariablesSetName());
    }

    public function replaceNotificationVariables(NotificationContract $notification, string $content, $notifiable): string
    {
        return strtr($content, $notification::templateVariablesClass()::getVariablesFromContent($notifiable, ...$notification->additionalDataForVariables()));
    }
}
