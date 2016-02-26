<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "group_key".
 *
 * @property integer $group_id
 * @property integer $key_id
 */
class GroupKey extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'group_key';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'key_id'], 'required'],
            [['group_id', 'key_id', 'id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'group_id' => 'Group ID',
            'key_id' => 'Key ID',
        ];
    }
}
