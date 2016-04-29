<?php

use yii\db\Migration;

class m160422_084437_create_projects_table extends Migration
{
    public function up()
    {
        $this->createTable('projects', [
            'id' => $this->integer(11),
            'title' => $this->string(500)->notNull(),
            'description' => $this->string(500),
            'gapi_profile_id' => $this->integer(11),
            'googlehost' => $this->string(30),
            'language' => $this->string(10),
            'status' => $this->integer(1),
            'upd_period' => $this->integer(11)->notNull()
        ]);

        $this->execute("
        INSERT INTO `projects` (`id`, `title`, `description`, `gapi_profile_id`, `googlehost`, `language`, `status`, `upd_period`) VALUES
            (2, 'http://www.reclamare.ua/', 'описание первого проекта', 86449576, '', '', 1, 864000),
            (6, 'http://kalyanchik.com.ua/', '', 107796667, 'google.com.ua', 'ua', 1, 864000),
            (7, 'http://www.medika.kiev.ua/', '', 71849257, 'google.com.ua', 'ua', 1, 864000);

        ALTER TABLE `projects`
            ADD PRIMARY KEY (`id`);
        ");
    }

    public function down()
    {
        $this->dropTable('projects');
    }
}
