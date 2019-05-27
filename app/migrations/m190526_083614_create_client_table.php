<?php
/**
 * @author Mykyta Popov <mp091689@gmail.com>
 */

use yii\db\Migration;

/**
 * Handles the creation of table `{{%client}}`.
 */
class m190526_083614_create_client_table extends Migration
{
    public const TABLE_NAME = '{{%client}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            self::TABLE_NAME,
            [
                'id' => $this->primaryKey(),
                'uuid' => $this->char(36)->notNull()->unique(),
                'first_name' => $this->string(100)->notNull(),
                'last_name' => $this->string(100)->notNull(),
                'sex' => $this->char(1)->notNull(),
                'birth' => $this->date()->notNull(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
