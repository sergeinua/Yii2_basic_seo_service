<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "key_position".
 *
 * @property integer $id
 * @property integer $key_id
 * @property integer $date
 * @property integer $time_from_today
 * @property integer $position
 * @property integer $fullDate
 */
class KeyPosition extends ActiveRecord
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

    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'date',
                ],
                'value' => function ($event) {
                    return mktime(0,0,0,date('m'),date('d'),date('Y'));
                },
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'time_from_today',
                ],
                'value' => function ($event) {
                    return time() - mktime(0,0,0,date('m'),date('d'),date('Y'));
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key_id', 'position'], 'required'],
            [['key_id', 'position', 'date', 'time_from_today'], 'integer'],
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
            'time_from_today' => 'Extra seconds',
            'position' => 'Position',
        ];
    }

    public function getFullDate()
    {
        return ($this->date + $this->time_from_today);
    }
}
