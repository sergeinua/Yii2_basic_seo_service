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
            'id' => $this->integer(11),
            'username' => $this->string(20),
            'firstName' => $this->string(50),
            'lastName' => $this->string(50),
            'email' => $this->string(50)->notNull(),
            'password' => $this->string(200)->notNull(),
            'created_at' => $this->integer(11)->notNull(),
            'role' => $this->string(5)->notNull(),
            'authKey' => $this->string(50)->notNull(),
        ]);

       $this->execute("
        INSERT INTO `user` (`id`, `username`, `firstName`, `lastName`, `email`, `password`, `created_at`, `role`, `authKey`) VALUES
            (9, 'admin', '', '', '', '$2y$13$1ssie9EIsLB6c.WiIkDJtOEYC3aoGWv4Ur.vq9pNxbZzvYFZJyKcC', 1458133294, 'admin', ''),
            (10, 'egor', 'егор', 'скворцов', '', '$2y$13\$aL3eBf8OA.LLIQrSg9OBZObp7quNq1IhtDBnZiIQtRmCp6nwAniF2', 1459438505, 'seo', ''),
            (11, 'oleg', 'олег', 'васильев', '', '$2y$13$1OX1t.2anVSBtVPG5DYOvOZB44Cri/mWJf7tY0jIxj/aCTFuriruK', 1459438568, 'user', ''),
            (12, 'new', '', '', '', '$2y$13\$nZTPRA3H/sqcm5n54XkEqOkMxJEy1sYa8PZuP19/xwqqOji9qY2zC', 1461740571, 'user', '');

        ALTER TABLE `user`
            ADD PRIMARY KEY (`id`);
        ");

    }

    public function safeDown()
    {
        $this->dropTable('user');
    }
}
