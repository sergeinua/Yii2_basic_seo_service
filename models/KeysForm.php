<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use yii\web\BadRequestHttpException;

class KeysForm extends Model
{
    public $id;
    public $status;
    public $group_id;
    public $title;

    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;


    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 500],
            ['status', 'in', 'range' => [self::STATUS_DISABLED, self::STATUS_ENABLED]],
            ['status', 'default', 'value' => self::STATUS_ENABLED],
            [['group_id', 'id'], 'integer']
        ];
    }


    public function save()
    {
        $transaction = Yii::$app->getDb()->beginTransaction();
        try{
            GroupKey::deleteAll([
                'key_id' => $this->id,
            ]);
            if(!($keyModel = $this->findKey($this->id))){
                $keyModel = new Keys();
            }
            if($keyModel->load($this->toArray(['id', 'title', 'status']),'')){
                if(!$keyModel->save()){
                    throw new BadRequestHttpException('Model is invalid!');
                }
            }
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


    public function findKey($id)
    {
        if(!$id)
            return false;
        return Keys::findOne($id);
    }
}