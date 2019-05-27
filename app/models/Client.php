<?php
declare(strict_types=1);
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

namespace app\models;

/**
 * This is the model class for table "client".
 *
 * @property int       $id
 * @property string    $uuid
 * @property string    $first_name
 * @property string    $last_name
 * @property string    $sex
 * @property string    $birth
 *
 * @property Account[] $accounts
 */
class Client extends \yii\db\ActiveRecord
{
    public const SEX_MALE = 'm';
    public const SEX_FEMALE = 'f';
    public const SEX_LIST = [
        self::SEX_MALE,
        self::SEX_FEMALE,
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'client';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['uuid', 'first_name', 'last_name', 'sex', 'birth'], 'required'],
            [['birth'], 'safe'],
            [['uuid'], 'string', 'max' => 36],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            [['sex'], 'in', 'range' => self::SEX_LIST],
            [['uuid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'sex' => 'Sex',
            'birth' => 'Birth',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::class, ['client_id' => 'id']);
    }
}
