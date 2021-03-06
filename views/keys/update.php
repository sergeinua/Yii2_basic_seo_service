<?php

use yii\helpers\Html;
use app\models\KeysForm;

/* @var $this yii\web\View */
/* @var $model app\models\Keys */

$this->title = 'Update Keys: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Keys', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="keys-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('keys', [
        'model' => $model,
    ]) ?>

</div>
