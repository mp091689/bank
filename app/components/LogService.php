<?php
declare(strict_types=1);
/**
 * @link   https://github.com/
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace app\components;

use app\components\interfaces\LogServiceInterface;
use app\models\interfaces\LogInterface;
use yii\base\Component;

/**
 * Class LogService
 *
 * @package app\components
 */
class LogService extends Component implements LogServiceInterface
{
    public function log(
        LogInterface $model,
        int $accountId,
        int $oldBalance,
        int $newBalance,
        int $amount,
        int $percent
    ): bool {
        $model->account_id = $accountId;
        $model->old_balance = $oldBalance;
        $model->new_balance = $newBalance;
        $model->amount = $amount;
        $model->percent = $percent;
        $model->created_at = (new \DateTime())->getTimestamp();

        return $model->save();
    }
}