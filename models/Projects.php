<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "projects".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $gapi_profile_id
 * @property integer $status
 * @property integer $upd_period
 */
class Projects extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'projects';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'status'], 'required'],
            [['status', 'upd_period', 'gapi_profile_id'], 'integer'],
            [['title', 'description'], 'string', 'max' => 500],
            [['googlehost'], 'string', 'max' => 30],
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
            'title' => 'url',
            'description' => 'Описание',
            'status' => 'Состояние',
            'upd_period' => 'Период обновления дней',
            'gapi_profile_id' => 'Google Analytics Profile ID',
        ];
    }

    public function getGroups()
    {
        return $this->hasMany(Groups::className(), ['id' => 'group_id'])->viaTable(ProjectGroup::tableName(), ['project_id' => 'id']);
    }

}
