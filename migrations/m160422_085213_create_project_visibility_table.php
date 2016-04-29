<?php

use yii\db\Migration;

class m160422_085213_create_project_visibility_table extends Migration
{
    public function up()
    {
        $this->createTable('project_visibility', [
            'id' => $this->string(32),
            'project_id' => $this->integer(11)->notNull(),
            'date' => $this->integer(11)->notNull(),
            'visibility' => $this->integer(3)->notNull()
        ]);

        $this->execute("
        INSERT INTO `project_visibility` (`id`, `project_id`, `date`, `visibility`) VALUES
            ('5b6cdce4d086b96e61d97d8cfe9dadcb', 2, 20160418, 50),
            ('c397386e553ad5d4d92606882d29ffdd', 2, 20160426, 50),
            ('d22872b053bc72991951707a1d83d07d', 2, 20160401, 50),
            ('d8ed34dbbaa23b899e95972a9336a612', 6, 20160418, 57),
            ('e81ca6497ce45a9f01e8aa07fc0016b3', 2, 20160407, 50);

        ALTER TABLE `project_visibility`
            ADD PRIMARY KEY (`id`);
        ");
    }

    public function down()
    {
        $this->dropTable('project_visibility');
    }
}
