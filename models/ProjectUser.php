<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project_user".
 *
 * @property string $id
 * @property integer $project_id
 * @property integer $user_id
 */
class ProjectUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'user_id'], 'required'],
            [['project_id', 'user_id'], 'integer'],
            [['id'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'project_id' => 'Project ID',
            'user_id' => 'User ID',
        ];
    }

    public function getProject(){
        return $this->hasOne(Projects::className(), ['id' => 'project_id']);
    }

    public function getUser(){
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
