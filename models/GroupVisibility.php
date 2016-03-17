<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "group_visibility".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $date
 * @property integer $visibility
 */
class GroupVisibility extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group_visibility';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'date', 'visibility'], 'required'],
            [['group_id', 'date', 'visibility'], 'integer'],
            [['id'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'date' => 'Date',
            'visibility' => 'Visibility',
        ];
    }
}
