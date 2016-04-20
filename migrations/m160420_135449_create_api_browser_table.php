<?php

use yii\db\Migration;

class m160420_135449_create_api_browser_table extends Migration
{
    public function up()
    {
        $this->createTable('api_browser_table', [
            'id' => $this->primaryKey()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_browser_table');
    }
}
