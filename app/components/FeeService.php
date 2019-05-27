<?php
declare(strict_types=1);
/**
 * @link   https://github.com/
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace app\components;


use app\components\interfaces\FeeServiceInterface;
use app\components\interfaces\LogServiceInterface;
use app\models\Account;
use app\models\FeeLog;
use yii\base\Component;

class FeeService extends Component implements FeeServiceInterface
{
    private const PERCENT_MIN = 5;
    private const PERCENT_MID = 6;
    private const PERCENT_MAX = 7;

    private const FEE_MIN = 50 * 100;
    private const FEE_MAX = 5000 * 100;

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
        $percent = $this->getPercent($oldBalance);
        $account->balance = $this->getBalance($oldBalance);
        if (!$account->save()) {
            throw new \Exception('Oops some thing went wrong!');
        }
        $this->logService->log(
            new FeeLog(),
            $account->id,
            $oldBalance,
            $account->balance,
            $oldBalance - $account->balance,
            $percent
        );
    }

    /**
     * @param int $balance
     *
     * @return int
     * @throws \Exception
     */
    private function getPercent(int $balance): int
    {
        switch ($balance) {
            case $balance < 1000 * 100:
                return self::PERCENT_MIN;
            case $balance >= 1000 * 100 && $balance < 10000 * 100:
                return self::PERCENT_MID;
            case $balance >= 10000 * 100:
                return self::PERCENT_MAX;
            default:
                throw new \Exception('Oops, something went wrong');
        }
    }

    /**
     * Calculates balance with fee.
     *
     * @param int $balance
     *
     * @return int
     * @throws \Exception
     */
    private function getBalance(int $balance): int
    {
        switch ($balance) {
            case $balance < 1000 * 100:
                $fee = $balance * self::PERCENT_MIN / 100;
                if ($fee < self::FEE_MIN) {
                    return (int)($balance - self::FEE_MIN);
                }

                return $balance - $fee;
            case $balance >= 1000 * 100 && $balance < 10000 * 100:
                return (int)($balance * self::PERCENT_MID / 100);
            case $balance >= 10000 * 100:
                $fee = (int)($balance * self::PERCENT_MAX / 100);
                if ($fee > self::FEE_MAX) {
                    return (int)($balance - self::FEE_MAX);
                }

                return (int)($balance - $fee);

            default:
                throw new \Exception('Oops, something went wrong');
        }
    }
}