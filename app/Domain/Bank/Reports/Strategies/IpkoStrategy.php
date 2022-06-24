<?php
/**
 * User: gmatk
 * Date: 21.06.2022
 * Time: 13:14
 */

namespace App\Domain\Bank\Reports\Strategies;

use App\Domain\Bank\Reports\Contracts\ReportStrategyContract;
use App\Domain\Bank\Reports\TransactionList;

/**
 *
 */
class IpkoStrategy implements ReportStrategyContract
{
    /**
     * @param string $data
     * @param string $format
     * @return array
     */
    public function decode(string $data, string $format): array
    {
        // TODO: Implement decode() method.
    }

    /**
     * @return string[]
     */
    public function getAvailableDecoders(): array
    {
        return [
            'csv',
            'pdf',
            'xls',
            'xlsx',
            'json'
        ];
    }

    /**
     * @param array $data
     * @return TransactionList
     */
    public function getTransactions(array $data): TransactionList
    {
        return new TransactionList();
    }

    /**
     * @param TransactionList $transactions
     * @return array
     */
    public function getCategories(TransactionList $transactions): array
    {
        // TODO: Implement getCategories() method.
    }
}
