<?php
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace app\models;

use app\models\interfaces\LogInterface;

/**
 * This is the model class for table "fee".
 *
 * @property int     $id
 * @property int     $account_id
 * @property int     $old_balance
 * @property int     $new_balance
 * @property int     $amount
 * @property int     $percent
 * @property string  $created_at
 *
 * @property Account $account
 */
class FeeLog extends \yii\db\ActiveRecord implements LogInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fee_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['account_id', 'old_balance', 'new_balance', 'amount', 'percent'],
                'integer',
            ],
            [
                ['created_at'],
                'safe',
            ],
            [
                ['account_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Account::class,
                'targetAttribute' => ['account_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'old_balance' => 'Old Balance',
            'new_balance' => 'New Balance',
            'amount' => 'Amount',
            'percent' => 'Percent',
            'created_at' => 'Transaction DateTime',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['id' => 'account_id']);
    }
}
