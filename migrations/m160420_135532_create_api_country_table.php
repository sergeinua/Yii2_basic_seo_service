<?php

use yii\db\Migration;

class m160420_135532_create_api_country_table extends Migration
{
    public function up()
    {
        $this->createTable('api_country', [
            'id' => $this->integer(11)->primaryKey(),
            'visits' => $this->string(20),
            'project_id' => $this->integer(11)->notNull(),
            'date' => $this->integer(11)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_country');
    }
}
