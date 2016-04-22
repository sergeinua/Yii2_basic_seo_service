<?php

use yii\db\Migration;

class m160420_135559_create_api_lng_table extends Migration
{
    public function up()
    {
        $this->createTable('api_lng', [
            'id' => $this->integer(11)->primaryKey(),
            'visits' => $this->integer(11),
            'language' => $this->string(10),
            'project_id' => $this->integer()->notNull(),
            'date' => $this->integer(11)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_lng');
    }
}
