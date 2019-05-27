<?php
declare(strict_types=1);
/**
 * @link   https://github.com/
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace app\components\interfaces;

use app\models\interfaces\LogInterface;

/**
 * Interface DepositLogServiceInterface
 */
interface LogServiceInterface
{
    /**
     * @param LogInterface $model
     * @param int          $accountId
     * @param int          $oldBalance
     * @param int          $newBalance
     * @param int          $amount
     * @param int          $percent
     *
     * @return bool
     */
    public function log(
        LogInterface $model,
        int $accountId,
        int $oldBalance,
        int $newBalance,
        int $amount,
        int $percent
    ): bool;
}