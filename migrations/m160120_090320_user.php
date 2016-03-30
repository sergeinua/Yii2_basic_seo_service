<?php

use yii\db\Schema;
use yii\db\Migration;

class m160120_090320_user extends Migration
{
    public function safeUp()
    {

        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * User identity table.
         */
        $this->createTable('user', [
            'id' => $this->integer(11)->primaryKey(),
            'username' => $this->string(20),
            'firstName' => $this->string(50),
            'lastName' => $this->string(50),
            'email' => $this->string(50)->notNull(),
            'password' => $this->string(200)->notNull(),
            'created_at' => $this->integer(11)->notNull(),
            'role' => $this->string(5)->notNull(),
            'authKey' => $this->string(50)->notNull(),
        ]);

//        $this->execute("");

    }

    public function safeDown()
    {
        $this->dropTable('user');
    }
}
