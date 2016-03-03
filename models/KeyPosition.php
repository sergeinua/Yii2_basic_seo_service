<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "key_position".
 *
 * @property integer $id
 * @property integer $key_id
 * @property string $date
 * @property integer $position
 */
class KeyPosition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'key_position';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key_id', 'date', 'position'], 'required'],
            [['key_id', 'position'], 'integer'],
            [['date'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key_id' => 'Key ID',
            'date' => 'Date',
            'position' => 'Position',
        ];
    }
}
