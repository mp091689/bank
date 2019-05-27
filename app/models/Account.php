<?php

namespace app\models;

/**
 * This is the model class for table "account".
 *
 * @property int          $id
 * @property int          $client_id
 * @property int          $percent
 * @property string       $balance
 * @property int          $created_at
 * @property int          $charge_at
 *
 * @property Client       $client
 * @property DepositLog[] $deposits
 * @property FeeLog[]     $fees
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'percent', 'balance', 'created_at', 'charge_at'], 'integer'],
            [['created_at', 'charge_at'], 'required'],
            [
                ['client_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Client::class,
                'targetAttribute' => ['client_id' => 'id'],
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
            'client_id' => 'Client ID',
            'percent' => 'Percent',
            'balance' => 'Balance',
            'created_at' => 'Created At',
            'charge_at' => 'Charge At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['id' => 'client_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeposits()
    {
        return $this->hasMany(DepositLog::class, ['account_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFees()
    {
        return $this->hasMany(FeeLog::class, ['account_id' => 'id']);
    }

    /**
     * @return self[]
     */
    public static function findAllToCharge(): array
    {
        $timeStamp = (new \DateTime())->getTimestamp();
        $result = self::find()->where(['<=', 'charge_at', $timeStamp])->all();

        return $result;
    }
}
