<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "api_source".
 *
 * @property integer $id
 * @property integer $visits
 * @property string $source
 * @property integer $project_id
 * @property integer $date
 */
class ApiSource extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_source';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visits', 'project_id', 'date'], 'integer'],
            [['project_id', 'date'], 'required'],
            [['source'], 'string', 'max' => 50],
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
            'source' => 'Source',
            'project_id' => 'Project ID',
            'date' => 'Date',
        ];
    }
}
