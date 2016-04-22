<?php

use yii\db\Migration;

class m160420_135449_create_api_browser_table extends Migration
{
    public function up()
    {
        $this->createTable('api_browser', [
            'id' => $this->integer(11)->primaryKey(),
            'pageviews' => $this->integer(),
            'visits' => $this->integer(),
            'browser' => $this->string(100),
            'browserVersion' => $this->string(25),
            'date' => $this->integer(11)->notNull(),
            'project_id' => $this->integer(11)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_browser');
    }
}
