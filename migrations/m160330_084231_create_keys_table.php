<?php

use yii\db\Migration;

class m160330_084231_create_keys_table extends Migration
{
    public function up()
    {
        $this->createTable('keys', [
            'id' => $this->integer(11)->primaryKey(),
            'title' => $this->string(500)->notNull(),
            'status' => $this->integer(1)->notNull(),
            'date_added' => $this->integer(11)->notNull(),
            'date_modified' => $this->integer(11),
        ]);
    }

    public function down()
    {
        $this->dropTable('keys');
    }
}
