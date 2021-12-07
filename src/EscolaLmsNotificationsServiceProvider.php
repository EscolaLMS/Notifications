<?php

namespace EscolaLms\Notifications;

use EscolaLms\Notifications\Listeners\NotifiableEventListener;
use EscolaLms\Notifications\Models\DatabaseNotification;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use EscolaLms\Notifications\Core\DatabaseChannel;
use Illuminate\Notifications\DatabaseNotification as IlluminateDatabaseNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

/**
 * SWAGGER_VERSION
 */
class EscolaLmsNotificationsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        Event::listen('*', function ($eventName, array $data) {
            if (Str::startsWith($eventName, 'EscolaLms')) {
                (new NotifiableEventListener())->handle($data[0]);
            }
        });
    }

    public function register()
    {
        $this->app->singleton(IlluminateDatabaseChannel::class, DatabaseChannel::class);
        $this->app->instance(IlluminateDatabaseNotification::class, new DatabaseNotification());
    }

    protected function bootForConsole(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
