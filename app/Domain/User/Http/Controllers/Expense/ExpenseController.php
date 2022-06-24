<?php
/**
 * User: gmatk
 * Date: 23.06.2022
 * Time: 20:28
 */

namespace App\Domain\User\Http\Controllers\Expense;

use App\Application\Exceptions\ExpenseException;
use App\Application\Services\Expense\ExpenseServiceContract;
use App\Domain\User\Http\Requests\StoreExpensesRequest;
use App\Interfaces\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 *
 */
class ExpenseController extends Controller
{
    /**
     * @param StoreExpensesRequest $request
     * @param ExpenseServiceContract $expenseService
     * @return JsonResponse
     * @throws ExpenseException
     */
    public function store(StoreExpensesRequest $request, ExpenseServiceContract $expenseService): JsonResponse
    {
        $expenseService->storeFromUploadedFile(
            $request->user()->getKey(),
            $request->input('bank'),
            $request->file('file')
        );

        return response()->json();
    }
}
