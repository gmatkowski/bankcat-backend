<?php
/**
 * User: gmatk
 * Date: 21.06.2022
 * Time: 13:14
 */

namespace App\Domain\Bank\Reports;

use App\Domain\Bank\Reports\Contracts\ReportStrategyContract;
use App\Domain\Bank\Reports\Strategies\IpkoStrategy;
use App\Domain\Bank\Reports\Strategies\MbankStrategy;

/**
 *
 */
class ReportService
{
    /**
     * @var array|string[]
     */
    public static array $strategies = [
        'mbank' => MbankStrategy::class,
        'ipko' => IpkoStrategy::class
    ];

    /**
     * @param ReportStrategyContract $strategy
     */
    public function __construct(private ReportStrategyContract $strategy)
    {

    }

    /**
     * @param ReportStrategyContract $strategy
     */
    public function setStrategy(ReportStrategyContract $strategy): void
    {
        $this->strategy = $strategy;
    }

    /**
     * @param string $data
     * @param string $format
     * @return array
     */
    public function decode(string $data, string $format): array
    {
        return $this->strategy->decode($data, $format);
    }

    /**
     * @param array $data
     * @return array
     */
    public function getCategories(array $data): array
    {
        return $this->strategy->getCategories($data);
    }
}
