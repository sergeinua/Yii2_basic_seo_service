<?php

use yii\db\Migration;

class m160422_085035_create_project_user_table extends Migration
{
    public function up()
    {
        $this->createTable('project_user', [
            'id' => $this->integer(11),
            'project_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull()
        ]);

        $this->execute("
        INSERT INTO `project_user` (`id`, `project_id`, `user_id`) VALUES
            ('92cc227532d17e56e07902b254dfad10', 2, 9),
            ('a0a080f42e6f13b3a2df133f073095dd', 2, 12);
        ALTER TABLE `project_user`
            ADD PRIMARY KEY (`id`);
        ");
    }

    public function down()
    {
        $this->dropTable('project_user');
    }
}
