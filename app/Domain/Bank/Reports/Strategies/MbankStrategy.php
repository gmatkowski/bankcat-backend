<?php
/**
 * User: gmatk
 * Date: 21.06.2022
 * Time: 13:12
 */

namespace App\Domain\Bank\Reports\Strategies;

use App\Domain\Bank\Reports\Contracts\ReportDecoderContract;
use App\Domain\Bank\Reports\Contracts\ReportStrategyContract;
use App\Domain\Bank\Reports\Decoders\Mbank\CsvDecoder;
use App\Domain\Bank\Reports\Decoders\Mbank\JsonDecoder;
use App\Domain\Bank\Reports\Exceptions\ReportDecoderException;
use Illuminate\Support\Facades\Validator;
use NumberFormatter;

/**
 *
 */
class MbankStrategy implements ReportStrategyContract
{
    /**
     * @var array|string[]
     */
    protected array $decoders = [
        'json' => JsonDecoder::class,
        'csv' => CsvDecoder::class
    ];

    /**
     * @param string $data
     * @param string $format
     * @return array
     * @throws ReportDecoderException
     */
    public function decode(string $data, string $format): array
    {
        if (!$decoderClass = ($this->decoders[strtolower($format)] ?? null)) {
            throw new ReportDecoderException('Decoder not found');
        }

        /**
         * @var ReportDecoderContract $decoder
         */
        $decoder = app($decoderClass);
        return $decoder->decode($data);
    }

    /**
     * @param array $data
     * @return array
     */
    public function getCategories(array $data): array
    {
        $transactions = $this->getTransactions($data);

        return array_values(
            array_unique(
                array_map(
                    static fn(array $data): string => $data['category'],
                    array_filter($transactions, fn(array $transaction): bool => $this->isValidReportData($transaction) && $this->isSpending($transaction))
                )
            )
        );
    }

    /**
     * @param array $transaction
     * @return bool
     */
    protected function isSpending(array $transaction): bool
    {
        $numberFormatter = new NumberFormatter('pl_PL', NumberFormatter::DECIMAL);
        $number = $numberFormatter->parse($transaction['amount']);

        return $number < 0;
    }

    /**
     * @param array $transactions
     * @return array
     */
    protected function getTransactions(array $transactions): array
    {
        if ($this->isReportWithHeader($transactions)) {
            array_shift($transactions);
        }

        return $transactions;
    }

    /**
     * @param array $transaction
     * @return bool
     */
    private function isValidReportData(array $transaction): bool
    {
        $validator = Validator::make($transaction, [
            'date' => [
                'required',
            ],
            'description' => [
                'required',
            ],
            'category' => [
                'required',
            ],
            'amount' => [
                'required',
            ]
        ]);

        return !$validator->fails();
    }

    /**
     * @param array $data
     * @return bool
     */
    private function isReportWithHeader(array $data): bool
    {
        return ($data[0]['date'] ?? '') === '#Data operacji';
    }

}
