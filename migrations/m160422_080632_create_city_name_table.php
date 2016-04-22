<?php

use yii\db\Migration;

class m160422_080632_create_city_name_table extends Migration
{
    public function up()
    {
        $this->createTable('city_name', [
            'criteriaId' => $this->integer(11),
            'name' => $this->string(100),
            'canonicalName' => $this->string(100),
            'parentId' => $this->integer(11),
            'countryCode' => $this->string(11),
            'targetType' => $this->string(20),
            'status' => $this->string(20)
        ]);
    }

    public function down()
    {
        $this->dropTable('city_name');
    }
}
