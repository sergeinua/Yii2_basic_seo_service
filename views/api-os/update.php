<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ApiOs */

$this->title = 'Update Api Os: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Api Os', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="api-os-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
