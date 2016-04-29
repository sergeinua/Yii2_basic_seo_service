<?php

use yii\db\Migration;

class m160330_075322_create_groups_table extends Migration
{
    public function up()
    {
        $this->createTable('groups', [
            'id' => $this->integer(11),
            'title' => $this->string(500)->notNull(),
            'description' => $this->string(500),
            'googlehost' => $this->string(30),
            'language' => $this->string(10),
            'status' => $this->string(1)->notNull(),
        ]);

        $this->execute("
        INSERT INTO `groups` (`id`, `title`, `description`, `googlehost`, `language`, `status`) VALUES
        (1, 'Группа 1', '', '', '', 1),
        (2, 'Группа 2', '', '', '', 0),
        (3, 'Группа 3', 'fwef', '', '', 1),
        (7, 'Первая группа', NULL, 'google.com.ua', 'ua', 1),
        (8, 'Тестовая группа', NULL, '', '', 1),
        (9, 'Медика первая группа', NULL, 'google.com.ua', 'ua', 1);

        ALTER TABLE `groups`
            ADD PRIMARY KEY (`id`);
        ");
    }

    public function down()
    {
        $this->dropTable('groups');
    }
}
