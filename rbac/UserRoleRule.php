<?php
namespace app\rbac;

use Yii;
use yii\rbac\Rule;

class UserRoleRule extends Rule
{
    public $name = 'userRole';

    public function execute($user, $item, $params)
    {
        if (!\Yii::$app->user->isGuest) {
            $group = \Yii::$app->user->identity->role;
            if ($item->name === 'admin') {
                return $group == 'admin';
            } elseif ($item->name === 'seo') {
                return $group == 'admin' || $group == 'seo';
            } elseif ($item->name === 'user') {
                return $group == 'admin' || $group == 'user';
            }
        }
        return true;
    }
}