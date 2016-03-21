<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "project_visibility".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $date
 * @property integer $visibility
 */
class ProjectVisibility extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project_visibility';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['project_id', 'date', 'visibility'], 'required'],
            [['project_id', 'date', 'visibility'], 'integer']
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
            'date' => 'Date',
            'visibility' => 'Visibility',
        ];
    }
}
