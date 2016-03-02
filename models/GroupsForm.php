<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use yii\web\BadRequestHttpException;

class GroupsForm extends Model
{
    public $id;
    public $status;
    public $title;
    public $project_id;

    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;


    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title'], 'string', 'max' => 500],
            ['status', 'in', 'range' => [self::STATUS_DISABLED, self::STATUS_ENABLED]],
            ['status', 'default', 'value' => self::STATUS_ENABLED],
            [['project_id', 'id'], 'integer']
        ];
    }


    public function save()
    {
        $transaction = Yii::$app->getDb()->beginTransaction();
        try{
            ProjectGroup::deleteAll([
                'group_id' => $this->id,
            ]);
            if(!($groupModel = $this->findGroup($this->id))){
                $groupModel = new Groups();
            }
            if($groupModel->load($this->toArray(['id', 'title', 'status']),'')){
                if(!$groupModel->save()){
                    throw new BadRequestHttpException('Model is invalid!');
                }
            }
            if(Projects::findOne($this->project_id)) {
                if(!(new ProjectGroup([
                    'group_id' => $groupModel->id,
                    'project_id' => $this->project_id,
                ]))->save())
                    throw new BadRequestHttpException('Group should exist for proceeding');
            }
            else
                throw new BadRequestHttpException('Group should exist for proceeding');
            $transaction->commit();
        }catch(\Exception $e){
            $transaction->rollBack();
            throw new $e;
        }
        return $groupModel;
    }


    public function findGroup($id)
    {
        if(!$id)
            return false;
        return Groups::findOne($id);
    }
}