<?php
declare(strict_types=1);
/**
 * @link   https://github.com/
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace app\components\interfaces;


use app\models\Account;

/**
 * Interface DepositServiceInterface
 */
interface DepositServiceInterface
{
    /**
     * Calculates deposit.
     *
     * @param Account $account
     */
    public function process(Account $account): void;
}
