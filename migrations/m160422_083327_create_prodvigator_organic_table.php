<?php

use yii\db\Migration;

class m160422_083327_create_prodvigator_organic_table extends Migration
{
    public function up()
    {
        $this->createTable('prodvigator_organic', [
            'id' => $this->integer(11)->primaryKey(),
            'region_queries_count' => $this->integer(11),
            'domain' => $this->string(50)->notNull(),
            'keyword' => $this->string(500)->notNull(),
            'url' => $this->string(500),
            'right_spell' => $this->string(100),
            'dynamic' => $this->integer(11),
            'found_results' => $this->integer(11),
            'url_crc' => $this->integer(11),
            'cost' => $this->float(),
            'concurrency' => $this->integer(11),
            'position' => $this->integer(11),
            'date' => $this->string(11),
            'keyword_id' => $this->integer(11),
            'subdomain' => $this->string(200),
            'region_queries_count_wide' => $this->string(11),
            'types' => $this->string(200),
            'geo_names' => $this->integer(11),
            'modified_at' => $this->integer(11)
        ]);
    }

    public function down()
    {
        $this->dropTable('prodvigator_organic');
    }
}
