<?php
namespace app\commands;

use app\rbac\UserRoleRule;
use Yii;
use yii\console\Controller;
//use \app\rbac\UserRoleRule;


class RbacController extends Controller
{
    const ROLE_USER = 20;
    const ROLE_SEO = 30;
    const ROLE_ADMIN = 40;

    public function actionInit()
    {
        $authManager = Yii::$app->authManager;
        $authManager->removeAll();


        // Create simple, based on action{$NAME} permissions
//        $login  = $authManager->createPermission('login');
//        $logout = $authManager->createPermission('logout');
//        $error  = $authManager->createPermission('error');
//        $signUp = $authManager->createPermission('sign-up');
//        $index  = $authManager->createPermission('index');
//        $view   = $authManager->createPermission('view');
//        $update = $authManager->createPermission('update');
//        $delete = $authManager->createPermission('delete');

        // Add permissions in Yii::$app->authManager
//        $authManager->add($login);
//        $authManager->add($logout);
//        $authManager->add($error);
//        $authManager->add($signUp);
//        $authManager->add($index);
//        $authManager->add($view);
//        $authManager->add($update);
//        $authManager->add($delete);

        /**
         * User role permission
         */
        $userRoleRule = new UserRoleRule();
        $authManager->add($userRoleRule);
        $userRolePermission = $authManager->createPermission('userRole');
        $userRolePermission->ruleName = $userRoleRule->name;
        $authManager->add($userRolePermission);
        // Create roles
        $guest  = $authManager->createRole('guest');
        $seo = $authManager->createRole('seo');
        $user = $authManager->createRole('user');
        $admin = $authManager->createRole('admin');

        $authManager->add($guest);
        $authManager->add($seo);
        $authManager->add($user);
        $authManager->add($admin);

        /**
         * Guest permissions
         */
        $authManager->addChild($guest, $userRolePermission);
        $authManager->addChild($seo, $guest);
        $authManager->addChild($user, $guest);
        $authManager->addChild($admin, $seo);
        $authManager->addChild($admin, $user);



        // Add rule "UserGroupRule" in roles
//        $admin->ruleName  = $userGroupRule->name;
//        $seo->ruleName  = $userGroupRule->name;
//        $user->ruleName = $userGroupRule->name;
//
//
//        // Add roles in Yii::$app->authManager
//        $authManager->add($admin);
//        $authManager->add($seo);
//        $authManager->add($user);
//
//        // Add permission-per-role in Yii::$app->authManager
//        // admin
//        $authManager->addChild($admin, $login);
//        $authManager->addChild($admin, $logout);
//        $authManager->addChild($admin, $error);
//        $authManager->addChild($admin, $signUp);
//        $authManager->addChild($admin, $index);
//        $authManager->addChild($admin, $view);
//
//        // seo
//        $authManager->addChild($seo, $login);
//        $authManager->addChild($seo, $index);
//
//        // user
//        $authManager->addChild($user, $login);
//        $authManager->addChild($user, $index);

    }
}
