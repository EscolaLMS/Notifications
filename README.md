# Notifications

Notifications package

[![swagger](https://img.shields.io/badge/documentation-swagger-green)](https://escolalms.github.io/notifications/)
[![codecov](https://codecov.io/gh/EscolaLMS/notifications/branch/main/graph/badge.svg?token=gBzpyNK8DQ)](https://codecov.io/gh/EscolaLMS/notifications)
[![phpunit](https://github.com/EscolaLMS/notifications/actions/workflows/test.yml/badge.svg)](https://github.com/EscolaLMS/notifications/actions/workflows/test.yml)
[![downloads](https://img.shields.io/packagist/dt/escolalms/notifications)](https://packagist.org/packages/escolalms/notifications)
[![downloads](https://img.shields.io/packagist/v/escolalms/notifications)](https://packagist.org/packages/escolalms/notifications)
[![downloads](https://img.shields.io/packagist/l/escolalms/notifications)](https://packagist.org/packages/escolalms/notifications)

## What does it do

This package is used for creating Notification with editable Templates.

## Installing

- `composer require escolalms/notifications`
- `php artisan migrate`
- `php artisan db:seed --class="EscolaLms\Notifications\Database\Seeders\NotificationsPermissionsSeeder"`

Notifications must implement `EscolaLms\Notifications\Core\NotificationContract`.

Notifications must be registered using `EscolaLms\Notifications\Facades\EscolaLmsNotifications` Facade, by calling `registerNotification` method.

To create default template for notifications, package should create NotificationSeeder which calls `createDefaultTemplates` method of the Facade.


## Endpoints

All the endpoints are defined in [![swagger](https://img.shields.io/badge/documentation-swagger-green)](https://escolalms.github.io/notifications/)

## Tests

Run `./vendor/bin/phpunit --filter 'EscolaLms\\Notifications\\Tests'` to run tests. See [tests](tests) folder as it's quite good staring point as documentation appendix.


### Admin panel

**Left menu**

![Menu](docs/menu.png "Menu")

**List of notifications**

![List of notifications](docs/list.png "List of notifications")

## Permissions

Permissions are defined in [seeder](vendor/escolalms/notifications/database/seeders/NotificationsPermissionsSeeder.php)
