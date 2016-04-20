<?php

use yii\db\Migration;

class m160420_135559_create_api_lng_table extends Migration
{
    public function up()
    {
        $this->createTable('api_lng_table', [
            'id' => $this->primaryKey()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_lng_table');
    }
}
