<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "api_lng".
 *
 * @property integer $id
 * @property integer $visits
 * @property string $language
 * @property integer $project_id
 * @property integer $date
 */
class ApiLng extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_lng';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visits', 'language', 'project_id', 'date'], 'required'],
            [['visits', 'project_id', 'date'], 'integer'],
            [['language'], 'string', 'max' => 10],
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
            'language' => 'Language',
            'project_id' => 'Project ID',
            'date' => 'Date',
        ];
    }
}
