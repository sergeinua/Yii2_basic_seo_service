<?php

use yii\db\Migration;

class m160420_135523_create_api_city_table extends Migration
{
    public function up()
    {
        $this->createTable('api_city_table', [
            'id' => $this->primaryKey()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_city_table');
    }
}
