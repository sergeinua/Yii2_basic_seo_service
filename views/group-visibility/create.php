<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\GroupVisibility */

$this->title = 'Create Group Visibility';
$this->params['breadcrumbs'][] = ['label' => 'Group Visibilities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-visibility-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
