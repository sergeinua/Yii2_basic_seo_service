<?php

use yii\db\Migration;

class m160330_080211_create_group_visibility_table extends Migration
{
    public function up()
    {
        $this->createTable('group_visibility', [
            'id' => $this->string(32),
            'group_id' => $this->integer(11)->notNull(),
            'date' => $this->integer(10)->notNull(),
            'visibility' => $this->integer(3)->notNull(),
        ]);

        $this->execute("
        INSERT INTO `group_visibility` (`id`, `group_id`, `date`, `visibility`) VALUES
            ('0a3230c0d50a1431b68bcc88eb1d0828', 9, 20160418, 100),
            ('24649b235adf94d8482cfad4296c8e79', 7, 20160401, 50),
            ('310568bcfc63de759a3a52496ad5b2c7', 8, 20160418, 86),
            ('e7d0b4a98fe41cbdc11bf76c4fae1cb1', 7, 20160418, 50),
            ('f537c160e34a73fa0ee3f4fa0990fbfb', 7, 20160415, 50);

        ALTER TABLE `group_visibility`
            ADD PRIMARY KEY (`id`);
        ");
    }

    public function down()
    {
        $this->dropTable('group_visibility');
    }
}
