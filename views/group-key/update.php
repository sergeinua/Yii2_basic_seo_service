<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\GroupKey */

$this->title = 'Update Group Key: ' . ' ' . $model->group_id;
$this->params['breadcrumbs'][] = ['label' => 'Group Keys', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->group_id, 'url' => ['view', 'id' => $model->group_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="group-key-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
