<?php

use yii\db\Migration;

class m160422_084437_create_projects_table extends Migration
{
    public function up()
    {
        $this->createTable('projects', [
            'id' => $this->integer(11)->primaryKey(),
            'title' => $this->string(500)->notNull(),
            'description' => $this->string(500),
            'gapi_profile_id' => $this->integer(11),
            'googlehost' => $this->string(30),
            'language' => $this->string(10),
            'status' => $this->integer(1),
            'upd_period' => $this->integer(11)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('projects');
    }
}
