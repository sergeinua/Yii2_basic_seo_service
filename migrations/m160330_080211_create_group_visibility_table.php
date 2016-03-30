<?php

use yii\db\Migration;

class m160330_080211_create_group_visibility_table extends Migration
{
    public function up()
    {
        $this->createTable('group_visibility', [
            'id' => $this->string(32)->primaryKey(),
            'group_id' => $this->integer(11)->notNull(),
            'date' => $this->integer(10)->notNull(),
            'visibility' => $this->integer(3)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('group_visibility');
    }
}
