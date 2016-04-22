<?php

use yii\db\Migration;

class m160422_075844_create_api_users_table extends Migration
{
    public function up()
    {
        $this->createTable('api_users', [
            'id' => $this->integer(11)->primaryKey(),
            'users' => $this->integer(11),
            'new_users' => $this->integer(11),
            'session_count' => $this->integer(11),
            'project_id' => $this->integer(11)->notNull(),
            'date' => $this->integer(11)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_users');
    }
}
