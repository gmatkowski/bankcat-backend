<?php

use App\Domain\User\Http\Controllers\Auth\AuthController;
use App\Domain\User\Http\Controllers\Auth\RegisterController;
use Illuminate\Routing\Router;

Route::group(['middleware' => ['guest:api']], function (Router $route) {
    $route->post('register', [RegisterController::class, 'register'])
        ->name('auth.register');

    $route->post('verify/{id}/{token}', [AuthController::class, 'verify'])
        ->name('auth.verify');
});

Route::group(['middleware' => ['auth:api']], function (Router $route) {
    $route->get('me', [AuthController::class, 'me'])
        ->name('auth.me');
    $route->post('logout', [AuthController::class, 'logout'])
        ->name('auth.logout');
});

Route::domain(config('app.frontend_url'))->group(function (Router $route) {
    $route->get('verify/{id}/{token}', function () {
        return response()->json();
    })->name('verification.verify');
});
