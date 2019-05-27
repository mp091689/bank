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
        $fee = $this->getFeeByDate($this->getFee((int)$account->balance), $account->created_at);
        $this->logService->log(
            new FeeLog(),
            $account->id,
            $oldBalance,
            (int)$account->balance - $fee,
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
     * Calculate fee depends on created date of account.
     *
     * @param int $fee
     * @param int $createdAt
     *
     * @return int
     * @throws \Exception
     */
    private function getFeeByDate(int $fee, int $createdAt): int
    {
        $now = new \DateTime();
        $diff = $now->diff(new \DateTime($createdAt));
        if ($diff->m > 0) {
            return $fee;
        }

        return $fee * ($diff->d / $now->modify('-1 moth')->format('t'));
    }

    /**
     * Calculates fee.
     *
     * @param int $balance
     *
     * @return int
     * @throws \Exception
     */
    private function getFee(int $balance): int
    {
        switch ($balance) {
            case $balance < 1000 * 100:
                $fee = (int)($balance * self::PERCENT_MIN / 100);
                if ($fee < self::FEE_MIN) {
                    return self::FEE_MIN;
                }

                return $fee;
            case $balance >= 1000 * 100 && $balance < 10000 * 100:
                return (int)($balance * self::PERCENT_MID / 100);
            case $balance >= 10000 * 100:
                $fee = (int)($balance * self::PERCENT_MAX / 100);
                if ($fee > self::FEE_MAX) {
                    return self::FEE_MAX;
                }

                return $fee;

            default:
                throw new \Exception('Oops, something went wrong');
        }
    }
}