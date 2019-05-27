<?php
declare(strict_types=1);
/**
 * @link   https://github.com/
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace app\components\interfaces;

use app\models\Account;

/**
 * Interface AccountServiceInterface
 */
interface AccountServiceInterface
{
    public function processDeposit(Account $account): void;
    public function processFee(Account $account): void;
}
