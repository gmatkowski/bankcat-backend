<?php
/**
 * User: gmatk
 * Date: 22.06.2022
 * Time: 08:58
 */

namespace App\Application\Dto\Expense;

/**
 *
 */
class ExpenseDto
{
    /**
     * @param string $uuid
     * @param string $name
     * @param int $amount
     * @param string $user_id
     * @param string $category_id
     */
    public function __construct(
        private string $uuid,
        private string $name,
        private int $amount,
        private string $user_id,
        private string $category_id
    ) {

    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @return string
     */
    public function getCategoryId(): string
    {
        return $this->category_id;
    }
}
