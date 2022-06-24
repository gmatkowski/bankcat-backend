<?php

use App\Domain\User\Http\Controllers\User\UserController;
use Illuminate\Routing\Router;

Route::group(['middleware' => ['auth:api']], function (Router $route) {
    $route->patch('/', [UserController::class, 'update'])
        ->name('user.update');
});
