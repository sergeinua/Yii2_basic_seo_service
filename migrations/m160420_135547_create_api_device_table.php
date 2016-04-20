<?php

use yii\db\Migration;

class m160420_135547_create_api_device_table extends Migration
{
    public function up()
    {
        $this->createTable('api_device_table', [
            'id' => $this->primaryKey()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_device_table');
    }
}
