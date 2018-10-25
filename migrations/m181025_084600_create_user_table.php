<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m181025_084600_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'balance' => $this->decimal(6, 2)->notNUll()->defaultValue(0),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->insert("user", ["username"=>"user1", "created_at" => time(), "updated_at" => time()]);
        $this->insert("user", ["username"=>"user2", "created_at" => time(), "updated_at" => time()]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
