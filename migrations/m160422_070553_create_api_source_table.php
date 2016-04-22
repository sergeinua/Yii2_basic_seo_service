<?php

use yii\db\Migration;

class m160422_070553_create_api_source_table extends Migration
{
    public function up()
    {
        $this->createTable('api_source', [
            'id' => $this->integer(11)->primaryKey(),
            'visits' => $this->integer(11),
            'source' => $this->string(50),
            'project_id' => $this->integer(11)->notNull(),
            'date' => $this->integer(11)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_source');
    }
}
