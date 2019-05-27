<?php
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

use yii\db\Migration;

/**
 * Handles the creation of table `{{%account}}`.
 */
class m190526_084100_create_account_table extends Migration
{
    public const TABLE_NAME = '{{%account}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            self::TABLE_NAME,
            [
                'id' => $this->primaryKey(),
                'client_id' => $this->integer(),
                'percent' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
                'balance' => $this->bigInteger()->unsigned()->notNull()->defaultValue(0),
                'created_at' => $this->integer()->unsigned()->notNull(),
                'charge_at' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            ]
        );

        $this->createIndex(
            'idx-charge_at',
            self::TABLE_NAME,
            'charge_at'
        );

        $this->createIndex(
            'idx-account-client_id',
            self::TABLE_NAME,
            'client_id'
        );

        $this->addForeignKey(
            'fk-account-client_id',
            self::TABLE_NAME,
            'client_id',
            'client',
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
            'fk-account-client_id',
            self::TABLE_NAME
        );

        $this->dropIndex(
            'idx-account-client_id',
            self::TABLE_NAME
        );

        $this->dropTable(self::TABLE_NAME);
    }
}
