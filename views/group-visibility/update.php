<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GroupVisibility */

$this->title = 'Update Group Visibility: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Group Visibilities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="group-visibility-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
