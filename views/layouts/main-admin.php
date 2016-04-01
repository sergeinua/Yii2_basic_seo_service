<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\widgets\Menu;

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
                                    ['label' => 'Новая группа', 'url' => ['/groups/create'], 'options' =>['class' => 'sidebar-list-item']],
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
            <!-- END show menu only for authorized users -->
            <?php endif; ?>
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
