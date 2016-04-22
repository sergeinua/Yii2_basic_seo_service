<?php

use yii\db\Migration;

class m160422_081530_create_prodvigator_data_table extends Migration
{
    public function up()
    {
        $this->createTable('prodvigator_data', [
            'id' => $this->string(50)->primaryKey(),
            'domain' => $this->string(50)->notNull(),
            'keywords' => $this->integer(11)->notNull(),
            'traff' => $this->integer(11),
            'new_keywords' => $this->integer(11),
            'out_keywords' => $this->integer(11),
            'rised_keywords' => $this->integer(11),
            'down_keywords' => $this->integer(11),
            'visible' => $this->float(),
            'cost_min' => $this->float(),
            'cost_max' => $this->float(),
            'ad_keywords' => $this->integer(11),
            'ads' => $this->integer(11),
            'date' => $this->string(11),
            'modified_at' => $this->integer(11)->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('prodvigator_data');
    }
}
