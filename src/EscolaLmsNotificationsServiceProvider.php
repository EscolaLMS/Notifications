<?php

namespace EscolaLms\Notifications;

use EscolaLms\Notifications\Http\Requests\TemplateCreateRequest;
use EscolaLms\Notifications\Http\Requests\TemplateUpdateRequest;
use EscolaLms\Notifications\Repositories\Contracts\TemplateRepositoryContract;
use EscolaLms\Notifications\Repositories\TemplateRepository;
use EscolaLms\Notifications\Services\Contracts\NotificationsServiceContract;
use EscolaLms\Notifications\Services\Contracts\TemplateServiceContract;
use EscolaLms\Notifications\Services\NotificationsService;
use EscolaLms\Notifications\Services\TemplateService;
use EscolaLms\Templates\Http\Requests\TemplateCreateRequest as BaseTemplateCreateRequest;
use EscolaLms\Templates\Http\Requests\TemplateUpdateRequest as BaseTemplateUpdateRequest;
use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract as BaseTemplateRepositoryContract;
use EscolaLms\Templates\Services\Contracts\TemplateServiceContract as BaseTemplateServiceContract;
use Illuminate\Support\ServiceProvider;

/**
 * SWAGGER_VERSION
 */
class EscolaLmsNotificationsServiceProvider extends ServiceProvider
{
    public $singletons = [
        NotificationsServiceContract::class => NotificationsService::class,
        TemplateRepositoryContract::class => TemplateRepository::class,
        TemplateServiceContract::class => TemplateService::class,
    ];

    public $bindings = [];

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function register()
    {
        $this->app->extend(BaseTemplateRepositoryContract::class, function ($service, $app) {
            return app(TemplateRepositoryContract::class);
        });

        $this->app->bind(BaseTemplateCreateRequest::class, TemplateCreateRequest::class);
        $this->app->bind(BaseTemplateUpdateRequest::class, TemplateUpdateRequest::class);
        $this->app->bind(BaseTemplateServiceContract::class, TemplateServiceContract::class);

        $this->app->bind('escola_notifications_facade', function () {
            return app(NotificationsService::class);
        });
    }

    protected function bootForConsole(): void
    {
    }
}
