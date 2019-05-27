<?php
declare(strict_types=1);
/**
 * @link   https://github.com/
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace app\commands;

use app\components\interfaces\AccountServiceInterface;
use app\models\Account;
use DateTime;
use yii\console\Controller;
use yii\di\Instance;

/**
 * Class DepositController
 */
class DepositController extends Controller
{
    /**
     * Runs deposit and fee calculation.
     *
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        /** @var AccountServiceInterface $accountService */
        $accountService = Instance::ensure(AccountServiceInterface::class);
        $accounts = Account::findAllToCharge();
        foreach ($accounts as $account) {
            $accountService->processDeposit($account);
            if ((new DateTime())->format('d') === '01') {
                $accountService->processFee($account);
            }
        }
    }
}