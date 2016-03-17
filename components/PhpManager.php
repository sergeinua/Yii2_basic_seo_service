<?php
namespace app\components;

use Yii;
use yii\base\InvalidParamException;
use yii\rbac\Assignment;

class PhpManager extends \yii\rbac\PhpManager

{
    /**
     * @var string
     */
    public $defaultRole = 'guest';

    /**
     * @var string
     */
    public $roleParam = 'role';

    public function getAssignments($userId)
    {
        $user = Yii::$app->getUser();
        if($user->isGuest){
            $assignment = new Assignment;
            $assignment->userId = $userId;
            $assignment->roleName = $this->defaultRole;
            $assignments[$assignment->roleName] = $assignment;
            return $assignments;
        }
        /** @var IdentityInterface|ActiveRecord|null $identity */
        $identity = $user->getIdentity();
        $assignments = parent::getAssignments($userId);
        $model = $userId === $user->getId()
            ? $identity
            : $identity::findOne($userId);
        if ($model) {
            $assignment = new Assignment;
            $assignment->userId = $userId;
            $assignment->roleName = $model->{$this->roleParam};
            $assignments[$assignment->roleName] = $assignment;
        }
        return $assignments;
    }}