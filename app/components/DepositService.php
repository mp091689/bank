<?php
declare(strict_types=1);
/**
 * @link   https://github.com/
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace app\components;


use app\components\interfaces\DepositServiceInterface;
use app\components\interfaces\LogServiceInterface;
use app\models\Account;
use app\models\DepositLog;
use yii\base\Component;

/**
 * Class DepositService
 */
class DepositService extends Component implements DepositServiceInterface
{
    /**
     * @var LogServiceInterface
     */
    private $logService;

    public function __construct(
        LogServiceInterface $logService,
        $config = []
    ) {
        $this->logService = new $logService;
        parent::__construct($config);
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function process(Account $account): void
    {
        $oldBalance = (int)$account->balance;
        $this->touchDeposit($account);
        $this->touchChargeAt($account);
        if (!$account->save()) {
            throw new \Exception('Oops some thing went wrong!');
        }
        $this->logService->log(
            new DepositLog(),
            $account->id,
            $oldBalance,
            (int)$account->balance,
            (int)$account->balance - $oldBalance,
            $account->percent
        );
    }

    /**
     * Calculates the balance with percents. Number will be round in favor of the bank.
     *
     * @param Account $account
     */
    private function touchDeposit(Account $account): void
    {
        $account->balance += (int)$account->balance * $account->percent / 100;
        $account->balance = floor($account->balance);
    }

    /**
     *  Calculates next payment day.
     *
     * @param Account $account
     *
     * @throws \Exception
     */
    private function touchChargeAt(Account $account): void
    {
        $createdAt = (new \DateTime())->setTimestamp($account->created_at);
        if ($account->charge_at === 0) {
            $chargeAt = (new \DateTime())->setTimestamp($account->created_at);
        } else {
            $chargeAt = (new \DateTime())->setTimestamp($account->charge_at);
        }

        $chargeAt->modify('first day of +1 month');

        if ($chargeAt->format('t') > $createdAt->format('j')) {
            while ($chargeAt->format('j') < $createdAt->format('j')) {
                $chargeAt->modify('+1 day');
            }
        } else {
            $chargeAt->modify('last day of this month');
        }
        $account->charge_at = $chargeAt->getTimestamp();
    }
}
