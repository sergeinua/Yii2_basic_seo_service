<?php

use yii\db\Migration;

class m160420_135608_create_api_os_table extends Migration
{
    public function up()
    {
        $this->createTable('api_os', [
            'id' => $this->integer(11)->primaryKey(),
            'visits' => $this->integer(11),
            'os' => $this->string(20),
            'project_id' => $this->integer(11)->notNull(),
            'date' => $this->integer(11)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_os');
    }
}
