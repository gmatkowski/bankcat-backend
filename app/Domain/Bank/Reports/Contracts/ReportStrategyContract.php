<?php
/**
 * User: gmatk
 * Date: 21.06.2022
 * Time: 13:12
 */

namespace App\Domain\Bank\Reports\Contracts;

/**
 *
 */
interface ReportStrategyContract
{
    /**
     * @param string $data
     * @param string $format
     * @return array
     */
    public function decode(string $data,  string $format): array;

    /**
     * @param array $data
     * @return array
     */
    public function getCategories(array $data): array;
}
