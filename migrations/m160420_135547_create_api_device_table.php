<?php

use yii\db\Migration;

class m160420_135547_create_api_device_table extends Migration
{
    public function up()
    {
        $this->createTable('api_device', [
            'id' => $this->integer(11)->primaryKey(),
            'visits' => $this->integer(11),
            'brand' => $this->string(50),
            'project_id' => $this->integer(11)->notNull(),
            'date' => $this->date(11)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_device');
    }
}