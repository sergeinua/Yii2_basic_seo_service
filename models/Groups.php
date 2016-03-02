<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "groups".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $project_id
 * @property Projects $project
 */
class Groups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'groups';
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(),
            [
                'project_id'
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'description'], 'string', 'max' => 500],
            ['status', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
        ];
    }

    public function getKeys()
    {
        return $this->hasMany(Keys::className(), ['id' => 'key_id'])->viaTable(GroupKey::tableName(), ['group_id' => 'id']);
    }

    public function getProject()
    {
        return $this->hasOne(Projects::className(), ['id' => 'project_id'])->viaTable(ProjectGroup::tableName(), ['group_id' => 'id']);
    }
}
