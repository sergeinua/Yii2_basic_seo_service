<?php

use yii\db\Migration;

class m160422_085035_create_project_user_table extends Migration
{
    public function up()
    {
        $this->createTable('project_user', [
            'id' => $this->integer(11)->primaryKey(),
            'project_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('project_user');
    }
}
