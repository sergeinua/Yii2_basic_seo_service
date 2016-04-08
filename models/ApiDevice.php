<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "api_device".
 *
 * @property integer $id
 * @property integer $visits
 * @property string $brand
 * @property integer $project_id
 * @property integer $date
 */
class ApiDevice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visits', 'project_id', 'date'], 'integer'],
            [['project_id', 'date'], 'required'],
            [['brand'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visits' => 'Visits',
            'brand' => 'Brand',
            'project_id' => 'Project ID',
            'date' => 'Date',
        ];
    }
}
