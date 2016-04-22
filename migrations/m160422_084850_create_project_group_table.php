<?php

use yii\db\Migration;

class m160422_084850_create_project_group_table extends Migration
{
    public function up()
    {
        $this->createTable('project_group', [
            'id' => $this->integer(11)->primaryKey(),
            'project_id' => $this->integer(11)->notNull(),
            'group_id' => $this->integer(11)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('project_group');
    }
}
