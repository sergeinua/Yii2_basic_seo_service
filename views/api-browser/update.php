<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ApiBrowser */

$this->title = 'Update Api Browser: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Api Browsers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="api-browser-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
