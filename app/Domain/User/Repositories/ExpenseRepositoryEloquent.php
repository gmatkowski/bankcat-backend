<?php

namespace App\Domain\User\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Application\Repositories\ExpenseRepository;
use App\Domain\User\Entities\Expense;
use App\Application\Validators\ExpenseValidator;

/**
 * Class ExpenseRepositoryEloquent.
 *
 * @package namespace App\Temp\Repositories;
 */
class ExpenseRepositoryEloquent extends BaseRepository implements ExpenseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Expense::class;
    }
}
