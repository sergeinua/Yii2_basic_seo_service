<?php

use yii\db\Migration;

class m160420_135532_create_api_country_table extends Migration
{
    public function up()
    {
        $this->createTable('api_country_table', [
            'id' => $this->primaryKey()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_country_table');
    }
}
