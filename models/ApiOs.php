<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "api_os".
 *
 * @property integer $id
 * @property integer $visits
 * @property string $os
 * @property integer $project_id
 * @property integer $date
 */
class ApiOs extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_os';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visits', 'project_id', 'date'], 'integer'],
            [['project_id', 'date'], 'required'],
            [['os'], 'string', 'max' => 20],
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
            'os' => 'Os',
            'project_id' => 'Project ID',
            'date' => 'Date',
        ];
    }
}
