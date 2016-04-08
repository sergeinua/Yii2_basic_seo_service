<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ApiSource */

$this->title = 'Create Api Source';
$this->params['breadcrumbs'][] = ['label' => 'Api Sources', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-source-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
