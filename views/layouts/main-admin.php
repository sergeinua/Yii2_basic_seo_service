<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\widgets\Menu;
use app\models\Projects;
use app\models\ProjectGroup;
use app\models\GroupKey;

AppAsset::register($this);
?>

<?php

/**
* admin
* seo
* user
**/
if(Yii::$app->user->identity)
    $user_role = Yii::$app->user->identity->role;
?>





<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'ADMIN',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
//            ['label' => 'Home', 'url' => ['/user/admin/index']],
//            ['label' => 'About', 'url' => ['/site/about']],
//            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ?
                ['label' => 'Login', 'url' => ['/site/login']] :
                [
                    'label' => 'Logout (' . Yii::$app->getUser()->identity->email . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <div class="row">
            <div class="col-xs-3 col-md-3 col-lg-3 admin-panel">
                <!-- BEGIN show menu only for authorized users -->
                <?php  if(isset($user_role)) : ?>
                    <?php  if($user_role == 'admin') : ?>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?= Yii::t('app', 'Меню'); ?></h3>
                            </div>
                            <?php
                            echo Menu::widget([
                                'options'=> ['class'=>'sidebar-list sidebar-e'],
                                'items' => [
                                    ['label' => 'Список проектов', 'url' => ['/projects/index'], 'options' =>['class' => 'sidebar-list-item']],
                                    ['label' => 'Пользователи проектов', 'url' => ['/project-user/index'], 'options' =>['class' => 'sidebar-list-item']],
                                    ['label' => 'Новый проект', 'url' => ['/projects/create'], 'options' =>['class' => 'sidebar-list-item']],
                                    ['label' => 'Список пользователей', 'url' => ['/user/index'], 'options' =>['class' => 'sidebar-list-item']],

                                    ['label' => Yii::t('app', 'Загрузить список проектов'), 'url' => ['projects/get-api-analytics-models'], 'options' =>['class' => 'sidebar-list-item']],
                                ]]);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php  if($user_role == 'seo') : ?>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?= Yii::t('app', 'Меню'); ?></h3>
                            </div>
                            <?php
                            echo Menu::widget([
                                'options'=> ['class'=>'sidebar-list sidebar-e'],
                                'items' => [
                                    ['label' => 'Список проектов', 'url' => ['/projects/index'], 'options' =>['class' => 'sidebar-list-item']],
                                ]]);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php  if($user_role == 'user') : ?>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?= Yii::t('app', 'Меню'); ?></h3>
                            </div>
                            <?php
                            echo Menu::widget([
                                'options'=> ['class'=>'sidebar-list sidebar-e'],
                                'items' => [
                                    ['label' => 'Список проектов', 'url' => ['/projects/index'], 'options' =>['class' => 'sidebar-list-item']],
                                ]]);
                            ?>
                        </div>
                    <?php endif; ?>
                    <!--MENU_ITEM_1_BEGIN seen in the project controller only -->
                    <?php // menu is visible for the defined controllers & actions
                    if(
                        // menu is visible for the projects controller
                        (Yii::$app->controller->id == 'projects' &&
                            // list of the defined actions
                            Yii::$app->controller->action->id == 'view' ||
                            Yii::$app->controller->action->id == 'show-prodvigator' ||
                            Yii::$app->controller->action->id == 'show-analytics'
                        ) || (
                        // menu is visible for the defined actions of the groups controller
                        Yii::$app->controller->id == 'groups' &&
                            // list of the defined actions
                            Yii::$app->controller->action->id == 'view'
                        ) || (
                        // menu is visible for the defined actions of the keys controller
                        Yii::$app->controller->id == 'keys' &&
                            // list of the defined actions
                            Yii::$app->controller->action->id == 'view'
                        )
                    ) : ?>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><?= Yii::t('app', 'Проект'); ?></h3>
                            </div>
                            <?php //hide analytics & prodvigator menu item for absent property in the current project
                            $project_id = Yii::$app->request->get('project_id') ? Yii::$app->request->get('project_id') : Yii::$app->request->get('id');
                            // getting $project_id in the groups controller
                            if(Yii::$app->controller->id == 'groups' && Yii::$app->controller->action->id == 'view') :
                                $group_id = Yii::$app->request->get('id');
                                $project_id = ProjectGroup::find()->where(['group_id' => $group_id])->one()->project_id;
                            endif;
                            // getting $project_id in the keys controller
                            if(Yii::$app->controller->id == 'keys' && Yii::$app->controller->action->id == 'view') :
                                $key_id = Yii::$app->request->get('id');
                                $group_id = GroupKey::find()->where(['key_id' => $key_id])->one()->group_id;
                                $project_id = ProjectGroup::find()->where(['group_id' => $group_id])->one()->project_id;
                            endif;
                            $gapi_profile = Projects::find()->where(['id' => $project_id])->one()->gapi_profile_id;
                            $prodvigator_token = Yii::$app->params['prodvigator_token'];
                            echo Menu::widget([
                                'options'=> ['class'=>'sidebar-list sidebar-e'],
                                'items' => [
                                    [
                                        'label' => 'Аналитика',
                                        'url' => ['/projects/show-analytics',
                                            'id' => $project_id],
                                        'options' =>['class' => 'sidebar-list-item'],
                                        'visible' => isset($gapi_profile) ? true : false,
                                    ],
                                    [
                                        'label' => 'Продвигатор',
                                        'url' => ['/projects/show-prodvigator',
                                            'project_id' => $project_id],
                                        'options' =>['class' => 'sidebar-list-item'],
                                        'visible' => isset($prodvigator_token) ? true : false,
                                    ],
                                    [
                                        'label' => 'AdWords',
                                        'url' => ['/projects/get-adwords-data',
                                            'project_id' => (Yii::$app->getRequest()->get('id') == null) ? Yii::$app->getRequest()->get('project_id') : Yii::$app->getRequest()->get('id')],
                                        'options' =>['class' => 'sidebar-list-item'],
                                    ],
                                ]]);
                            ?>
                        </div>
                    <?php endif; ?>
                    <!--MENU_ITEM_1_END -->


            <?php endif; ?>
            <!-- END show menu only for authorized users -->
        </div>

            <div class="col-xs-9 col-md-9 col-lg-9 <?= Yii::$app->getUser()->isGuest ? '' : 'logged-in' ?>">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= $content ?>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
