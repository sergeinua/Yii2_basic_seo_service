<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "api_users".
 *
 * @property integer $id
 * @property integer $users
 * @property integer $new_users
 * @property integer $session_count
 * @property integer $project_id
 * @property integer $date
 */
class ApiUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['users', 'new_users', 'session_count', 'project_id', 'date'], 'integer'],
            [['project_id', 'date'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'users' => 'Users',
            'new_users' => 'New Users',
            'session_count' => 'Session Count',
            'project_id' => 'Project ID',
            'date' => 'Date',
        ];
    }
}
