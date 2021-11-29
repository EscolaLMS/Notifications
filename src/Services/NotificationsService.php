<?php

namespace EscolaLms\Notifications\Services;

use EscolaLms\Notifications\Core\NotificationContract;
use EscolaLms\Notifications\Models\Template;
use EscolaLms\Notifications\Repositories\Contracts\TemplateRepositoryContract;
use EscolaLms\Notifications\Services\Contracts\NotificationsServiceContract;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;
use ReflectionClass;

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
        /** @var NotificationContract|string $notificationClass */
        if (!is_a($notificationClass, NotificationContract::class, true)) {
            throw new InvalidArgumentException("Notification Class must implement Notification Contract");
        }
        $class = new ReflectionClass($notificationClass);
        if ($class->isAbstract()) {
            throw new InvalidArgumentException("Notification Class must not be Abstract");
        }

        $this->notifications[] = $notificationClass;
        foreach ($notificationClass::availableVia() as $notificationRoute) {
            $this->variablesService::addToken($notificationClass::templateVariablesClass(), $notificationRoute, $notificationClass::templateVariablesSetName());
        }
    }

    private function findTemplateForNotificationClass(string $notificationClass, ?string $channel = null): ?Template
    {
        /** @var NotificationContract|string $notificationClass */
        if (is_null($channel) || !in_array($channel, $notificationClass::availableVia())) {
            return null;
        }
        return $this->templateRepository->findDefaultForTypeAndSubtype($channel, $notificationClass::templateVariablesSetName());
    }

    public function createDefaultTemplates(?string $notificationClass = null): void
    {
        if (Schema::hasTable('templates')) {
            if (is_null($notificationClass)) {
                foreach ($this->notifications as $notificationClass) {
                    $this->createDefaultTemplates($notificationClass);
                }
            } else {
                foreach ($notificationClass::availableVia() as $notificationRoute) {
                    $template = $this->findTemplateForNotificationClass($notificationClass, $notificationRoute);
                    if (is_null($template)) {
                        $this->templateRepository->create([
                            'is_default' => true,
                            'name' => $notificationRoute . ':' . $notificationClass::templateVariablesSetName(),
                            'type' => $notificationRoute,
                            'vars_set' => $notificationClass::templateVariablesSetName(),
                            'title' => $notificationClass::defaultTitleTemplate(),
                            'content' => $notificationClass::defaultContentTemplate(),
                        ]);
                    }
                }
            }
        }
    }

    public function findTemplateForNotification(NotificationContract $notification, ?string $channel = null): ?Template
    {
        return $this->findTemplateForNotificationClass(get_class($notification), $channel);
    }

    public function replaceNotificationVariables(NotificationContract $notification, string $content, $notifiable): string
    {
        return strtr($content, $notification::templateVariablesClass()::getVariablesFromContent($notifiable, ...$notification->additionalDataForVariables($notifiable)));
    }

    public function findDatabaseNotification(string $notificationClass, Model $notifiable, array $data): ?DatabaseNotification
    {
        /** @var NotificationContract|string $notificationClass */
        if (!is_a($notificationClass, NotificationContract::class, true)) {
            throw new InvalidArgumentException("Notification Class must implement Notification Contract");
        }
        return DatabaseNotification::query()
            ->where('notifiable_id', $notifiable->getKey())
            ->where('notifiable_type', $notifiable->getMorphClass())
            ->where('type', $notificationClass)
            ->whereJsonContains('data', $data)
            ->latest()
            ->first();
    }
}
