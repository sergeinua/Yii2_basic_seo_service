<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GroupKey */

$this->title = 'Create Group Key';
$this->params['breadcrumbs'][] = ['label' => 'Group Keys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-key-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
