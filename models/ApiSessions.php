<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "api_sessions".
 *
 * @property integer $id
 * @property string $session_duration
 * @property integer $pageviews
 * @property integer $bounces
 * @property integer $session_duration_bucket
 * @property integer $project_id
 * @property integer $date
 */
class ApiSessions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_sessions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['pageviews', 'project_id', 'date', 'bounces'], 'integer'],
            [['project_id', 'date'], 'required'],
            [['session_duration', 'session_duration_bucket'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'session_duration' => 'Session Duration',
            'pageviews' => 'Pageviews',
            'bounces' => 'Bounces',
            'session_duration_bucket' => 'Session Duration Bucket',
            'project_id' => 'Project ID',
            'date' => 'Date',
        ];
    }
}
