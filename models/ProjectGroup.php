<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project_group".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $group_id
 */
class ProjectGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'group_id'], 'required'],
            [['project_id', 'group_id'], 'integer']
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
            'group_id' => 'Group ID',
        ];
    }
}
