<?php

use yii\db\Migration;

class m160422_070252_create_api_sessions_table extends Migration
{
    public function up()
    {
        $this->createTable('api_sessions', [
            'id' => $this->integer(11)->primaryKey(),
            'session_duration' => $this->integer(11),
            'pageviews' => $this->integer(11),
            'bounces' => $this->integer(11),
            'session_duration_bucket' => $this->string(11),
            'project_id' => $this->integer(11)->notNull(),
            'date' => $this-> integer(11)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('api_sessions');
    }
}
