<?php

use App\Domain\User\Http\Controllers\Expense\ExpenseController;
use Illuminate\Routing\Router;

Route::group(['middleware' => ['auth:api']], function (Router $route) {
    $route->post('/', [ExpenseController::class, 'store'])->name('expense.store');
});
