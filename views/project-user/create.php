<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProjectUser */

$this->title = 'Create Project User';
$this->params['breadcrumbs'][] = ['label' => 'Project Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-user-create">

    <h1><?= Yii::t('app', 'Добавить пользователя к проекту') ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'project_list' => $project_list,
        'user_list' => $user_list,
    ]) ?>

</div>
