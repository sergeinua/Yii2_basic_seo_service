<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "api_browser".
 *
 * @property integer $id
 * @property integer $pageviews
 * @property integer $visits
 * @property string $browser
 * @property string $browserVersion
 * @property integer $date
 * @property integer $project_id
 */
class ApiBrowser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_browser';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pageviews', 'visits', 'date', 'project_id'], 'integer'],
            [['date'], 'required'],
            [['browser', 'browserVersion'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pageviews' => 'Pageviews',
            'visits' => 'Visits',
            'browser' => 'Browser',
            'browserVersion' => 'Browser Version',
            'date' => 'Date',
        ];
    }
}
