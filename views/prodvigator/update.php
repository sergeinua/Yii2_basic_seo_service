<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProdvigatorData */

$this->title = 'Update Prodvigator Data: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Prodvigator Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="prodvigator-data-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
