<?php

namespace App\Domain\Bank\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Application\Repositories\BankRepository;
use App\Domain\Bank\Entities\Bank;
use App\Application\Validators\BankValidator;

/**
 * Class BankRepositoryEloquent.
 *
 * @package namespace App\Temp\Repositories;
 */
class BankRepositoryEloquent extends BaseRepository implements BankRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model(): string
    {
        return Bank::class;
    }

}
