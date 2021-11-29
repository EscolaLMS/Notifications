<?php

use EscolaLms\Notifications\Http\Controllers\NotificationsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/notifications'], function () {
    Route::get('/{user?}', [NotificationsController::class, 'index']);
});

Route::group(['middleware' => ['auth:api'], 'prefix' => 'api/admin/notifications'], function () {
});
