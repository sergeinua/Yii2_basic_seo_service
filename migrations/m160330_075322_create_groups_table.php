<?php

use yii\db\Migration;

class m160330_075322_create_groups_table extends Migration
{
    public function up()
    {
        $this->createTable('groups', [
            'id' => $this->integer(11)->primaryKey(),
            'title' => $this->string(500)->notNull(),
            'description' => $this->string(500),
            'googlehost' => $this->string(30),
            'language' => $this->string(10),
            'status' => $this->string(1)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('groups');
    }
}
