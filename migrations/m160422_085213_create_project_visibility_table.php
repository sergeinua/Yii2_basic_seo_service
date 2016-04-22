<?php

use yii\db\Migration;

class m160422_085213_create_project_visibility_table extends Migration
{
    public function up()
    {
        $this->createTable('project_visibility', [
            'id' => $this->string(32)->primaryKey(),
            'project_id' => $this->integer(11)->notNull(),
            'date' => $this->integer(11)->notNull(),
            'visibility' => $this->integer(3)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('project_visibility');
    }
}
