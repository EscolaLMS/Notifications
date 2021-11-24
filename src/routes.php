<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/notifications'], function () {
});

Route::group(['middleware' => ['auth:api'], 'prefix' => 'api/admin/notifications'], function () {
});
