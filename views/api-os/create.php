<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ApiOs */

$this->title = 'Create Api Os';
$this->params['breadcrumbs'][] = ['label' => 'Api Os', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-os-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
