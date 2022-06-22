<?php
/**
 * User: gmatk
 * Date: 21.06.2022
 * Time: 09:43
 */

namespace App\Domain\User\Projectors;

use App\Application\Events\Expense\ExpenseCreated;
use App\Domain\User\Entities\Expense;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

/**
 *
 */
class ExpenseProjector extends Projector
{

    /**
     *
     */
    public function onExpenseCreated(ExpenseCreated $event): void
    {
        $expense = new Expense();
        $expense->id = $event->getDto()->getUuid();
        $expense->name = $event->getDto()->getName();
        $expense->amount = $event->getDto()->getAmount();
        $expense->user()->associate($event->getDto()->getUserId());
        $expense->category()->associate($event->getDto()->getCategoryId());
        $expense->save();
    }

    /**
     *
     */
    public function onStartingEventReplay()
    {
        Expense::truncate();
    }
}
