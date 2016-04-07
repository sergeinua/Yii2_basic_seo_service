<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ApiDevice */

$this->title = 'Create Api Device';
$this->params['breadcrumbs'][] = ['label' => 'Api Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-device-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
