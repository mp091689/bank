<?php
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

use yii\db\Migration;

/**
 * Handles the creation of table `{{%deposit_log}}`.
 */
class m190526_100111_create_deposit_table extends Migration
{
    public const TABLE_NAME = '{{%deposit_log}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            self::TABLE_NAME, [
                'id' => $this->primaryKey(),
                'account_id' => $this->integer(),
                'old_balance' => $this->bigInteger()->notNull()->defaultValue(0),
                'new_balance' => $this->bigInteger()->notNull()->defaultValue(0),
                'amount' => $this->integer()->notNull()->defaultValue(0),
                'percent' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
                'created_at' => $this->integer()->unsigned()->notNull(),
            ]
        );

        $this->createIndex(
            'idx-created_at',
            self::TABLE_NAME,
            'created_at'
        );

        $this->createIndex(
            'idx-deposit-account_id',
            self::TABLE_NAME,
            'account_id'
        );

        $this->addForeignKey(
            'fk-deposit-account_id',
            self::TABLE_NAME,
            'account_id',
            'account',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-deposit-account_id',
            self::TABLE_NAME
        );

        $this->dropIndex(
            'idx-deposit-account_id',
            self::TABLE_NAME
        );

        $this->dropIndex(
            'idx-created_at',
            self::TABLE_NAME
        );

        $this->dropTable(self::TABLE_NAME);
    }
}
