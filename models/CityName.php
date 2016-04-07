<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "city_name".
 *
 * @property integer $criteriaId
 * @property string $name
 * @property string $canonicalName
 * @property integer $parentId
 * @property string $countryCode
 * @property string $targetType
 * @property string $status
 * @property integer $project_id
 */
class CityName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city_name';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['criteriaId', 'name', 'canonicalName', 'parentId', 'countryCode', 'targetType', 'status', 'project_id'], 'required'],
            [['criteriaId', 'parentId', 'project_id'], 'integer'],
            [['name', 'canonicalName', 'countryCode'], 'string', 'max' => 100],
            [['targetType', 'status'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'criteriaId' => 'Criteria ID',
            'name' => 'Name',
            'canonicalName' => 'Canonical Name',
            'parentId' => 'Parent ID',
            'countryCode' => 'Country Code',
            'targetType' => 'Target Type',
            'status' => 'Status',
        ];
    }
}
