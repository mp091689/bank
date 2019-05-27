<?php
declare(strict_types=1);
/**
 * @link   https://github.com/
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace app\components;

use app\components\interfaces\AccountServiceInterface;
use app\components\interfaces\DepositServiceInterface;
use app\components\interfaces\FeeServiceInterface;
use app\models\Account;
use yii\base\Component;

class AccountService extends Component implements AccountServiceInterface
{
    /**
     * @var DepositServiceInterface
     */
    private $depositService;

    /**
     * @var FeeServiceInterface
     */
    private $feeService;

    public function __construct(
        DepositServiceInterface $depositService,
        FeeServiceInterface $feeService,
        $config = []
    ) {
        $this->depositService = $depositService;
        $this->feeService = $feeService;
        parent::__construct($config);
    }

    /**
     * Calculates deposit.
     *
     * @param Account $account
     */
    public function processDeposit(Account $account): void
    {
        $this->depositService->process($account);
    }

    /**
     * Calculates fee.
     *
     * @param Account $account
     */
    public function processFee(Account $account): void
    {
        $this->feeService->process($account);
    }
}
