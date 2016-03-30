<?php

use yii\db\Migration;

class m160330_075932_create_group_key_table extends Migration
{
    public function up()
    {
        $this->createTable('group_key', [
            'id' => $this->integer(11)->primaryKey(),
            'group_id' => $this->integer(11)->notNull(),
            'key_id' => $this->integer(11)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('group_key');
    }
}
