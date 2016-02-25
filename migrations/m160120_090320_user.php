<?php

use yii\db\Schema;
use yii\db\Migration;

class m160120_090320_user extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * User identity table.
         */
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'description' => $this->text(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string(),
            'email' => $this->string()->notNull()->unique(),
            'is_juridical_person' => 'TINYINT(1) NOT NULL DEFAULT \'0\'',
            'user_type' => 'TINYINT(1) NOT NULL DEFAULT \'5\'',

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull()
        ], $tableOptions);

        $this->execute("
            INSERT INTO `user` (`id`, `title`, `description`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `is_juridical_person`, `user_type`, `status`, `created_at`) VALUES
            (1, 'title', 'description', '8a59v0nC_3bJs96wEVcsP2i3C-GBKdoO', '$2y$13\$qindC1fA.BBLxRclIX8UyuNfiKR.L8wBXr.l8VcHUseVQmUY6BwI.', NULL, 'demo@admin.com', 0, 1, 10, 123123),
            (2, 'rerere', 'description', '4wrtwe', '$2y$13\$c.iNG5z7OfBsczgG51tiOu3EZTCwxVdvkKNzc8mERz2bDqpZ9Zjbe', 'wertwrt', 'wrtw@mail.ru', 0, 5, 10, 1453380349),
            (3, 'mex', 'description', '', '$2y$13\$uvu6ZP.o1aEBYpc5U1NHROx4CVe4UxZJZMC7LE1GvmY/dvV38LrMG', NULL, 'pav@pav.com', 0, 5, 10, 1453362225),
            (4, 'olga', 'description', '', '$2y$13\$cR03E4ff2TJV.waFfnCMR.G5WTR6lFtPGD7uVktJVXW2VGtemAKM2', NULL, 'olga@email.ls', 0, 5, 10, 1453366992),
            (12, 'Сотрудник', 'Сотрудник компании', '', '$2y$13\$gw4qMcZrFW5IzJfbQdMTR.mPc3LPTKVf1pTrnjYeChW0kgQVrITH.', NULL, 'staff@company.ru', 0, 10, 10, 1455279559);

            ALTER TABLE `user`
              ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

            ALTER TABLE `user`
              MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
        ");

    }

    public function safeDown()
    {
        $this->dropTable('user');
    }
}
