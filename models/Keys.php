<?php

namespace app\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;

/**
 * This is the model class for table "keys".
 *
 * @property integer $id
 * @property string $title
 * @property integer $group_id
 * @property integer $date_added
 * @property integer $date_modified
 * @property Groups $group
 */
class Keys extends \yii\db\ActiveRecord
{

    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'keys';
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(),
            [
                'group_id'
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
            [['title'], 'string', 'max' => 500],
            ['status', 'in', 'range' => [self::STATUS_DISABLED, self::STATUS_ENABLED]],
            ['status', 'default', 'value' => self::STATUS_ENABLED],
            ['group_id', 'safe'],
            [['date_added', 'date_modified'], 'integer'],
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
            'status' => 'Status',
        ];
    }
/*
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(),[
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    self::EVENT_AFTER_FIND => 'group_id'
                ],
                'value' => function($e) {
                    if($this->group)
                        return $this->group->id;
                    return null;
                }
            ],

        ]);
    }

    public function save($runValidation=true, $attributes=null)
    {
        $transaction = Yii::$app->getDb()->beginTransaction();
        try{
            GroupKey::deleteAll([
                'key_id' => $this->id,
            ]);
        $s = parent::save($runValidation, parent::attributes());
        if(Groups::findOne($this->group_id))
            (new GroupKey([
                'key_id' => $this->id,
                'group_id' => $this->group_id,
            ]))->save();
        else
            throw new BadRequestHttpException('Group should exist for proceeding');
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            throw new $e;
        }
        return true;
    }
*/

    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['id' => 'group_id'])->viaTable(GroupKey::tableName(), ['key_id' => 'id']);
    }

    public function getPosition()
    {
//        return $this->hasOne(KeyPosition::className(), ['key_id' => 'id'])->orderBy('date DESC, time_from_today DESC');
        return $this->hasOne(KeyPosition::className(), ['key_id' => 'id'])->orderBy('id DESC');
    }

    public function getPrevious_position()
    {
        return $this->hasMany(KeyPosition::className(), ['key_id' => 'id'])->orderBy('time_from_today DESC')->orderBy('date DESC');
    }
}
