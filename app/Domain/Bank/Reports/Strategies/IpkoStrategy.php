<?php
/**
 * User: gmatk
 * Date: 21.06.2022
 * Time: 13:14
 */

namespace App\Domain\Bank\Reports\Strategies;

use App\Domain\Bank\Reports\Contracts\ReportStrategyContract;

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
        return  [];
    }

    /**
     * @param array $data
     * @return array
     */
    public function getCategories(array $data): array
    {
        return [];
    }

}
