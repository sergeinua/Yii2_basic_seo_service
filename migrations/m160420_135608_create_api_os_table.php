<?php

use yii\db\Migration;

class m160420_135608_create_api_os_table extends Migration
{
    public function up()
    {
        $this->createTable('api_os_table', [
            'id' => $this->primaryKey()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_os_table');
    }
}
