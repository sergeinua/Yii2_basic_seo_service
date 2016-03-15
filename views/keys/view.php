<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use miloschuman\highcharts\Highcharts;

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

<?php
    // applying the correct timezone
    date_default_timezone_set('Europe/Kiev');
    $quan = count($model->previous_position) - 1;
    $i=0;
    for($i=0; $i<=$quan; $i++){
        $dates[$i] = date("F j, Y, g:i a", $model->previous_position[$i]->fullDate);
        $positions[$i] = $model->previous_position[$i]->position;
    }
?>

    <?= Highcharts::widget([
        'options' => [
            'title' => ['text' => 'Dynamics'],
            'xAxis' => [
                'categories' => $dates,
            ],
            'yAxis' => [
                'title' => ['text' => 'Position']
            ],
            'series' => [
                ['name' => $this->title, 'data' => $positions],

            ]
        ]
    ]); ?>


</div>
