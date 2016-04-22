<?php

use yii\db\Migration;

class m160420_135523_create_api_city_table extends Migration
{
    public function up()
    {
        $this->createTable('api_city', [
            'city_id' => $this->integer(11)->primaryKey(),
            'country_iso' => $this->string(10),
            'visits' => $this->integer(11),
            'created_at' => $this->integer(11)->notNull(),
            'project_id' => $this->integer(11)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_city');
    }
}
