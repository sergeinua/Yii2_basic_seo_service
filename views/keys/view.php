<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Keys */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Keys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="keys-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Обновить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Создать'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'status',
                'value' => $model->status == 0 ? Yii::t('app', 'Неактивно') : Yii::t('app', 'Активно'),
            ]
        ],
    ]) ?>

</div>
