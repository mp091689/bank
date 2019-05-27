<?php
declare(strict_types=1);
/**
 * @link   https://github.com/
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace app\components\interfaces;


use app\models\Account;

/**
 * Interface FeeServiceInterface
 */
interface FeeServiceInterface
{
    /**
     * Calculates fee for specified account.
     *
     * @param Account $account
     *
     * @return void
     */
    public function process(Account $account): void;
}