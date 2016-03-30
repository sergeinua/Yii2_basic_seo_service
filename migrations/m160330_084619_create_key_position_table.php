<?php

use yii\db\Migration;

class m160330_084619_create_key_position_table extends Migration
{
    public function up()
    {
        $this->createTable('key_position', [
            'id' => $this->primaryKey(),
            'key_id' => $this->integer(11)->notNull(),
            'date' => $this->integer(11)->notNull(),
            'time_from_today' => $this->integer(11)->notNull(),
            'position' =>$this->integer(3)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable('key_position');
    }
}
