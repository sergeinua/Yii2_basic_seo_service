<?php

use yii\db\Migration;

class m160422_084850_create_project_group_table extends Migration
{
    public function up()
    {
        $this->createTable('project_group', [
            'id' => $this->integer(11),
            'project_id' => $this->integer(11)->notNull(),
            'group_id' => $this->integer(11)->notNull()
        ]);

        $this->execute("
        INSERT INTO `project_group` (`id`, `project_id`, `group_id`) VALUES
            (4, 2, 1),
            (13, 2, 7),
            (14, 6, 8),
            (16, 7, 9);

        ALTER TABLE `project_group`
            ADD PRIMARY KEY (`id`);
        ");
    }

    public function down()
    {
        $this->dropTable('project_group');
    }
}
