<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use miloschuman\highcharts\Highcharts;
use kartik\daterange\DateRangePicker;
use yii\bootstrap\ActiveForm;

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
    //period is not defined
    for($i=0; $i<=$quan; $i++){
        $dates[$i] = date("F j, Y, g:i a", $model->previous_position[$i]->fullDate);
        $positions[$i] = $model->previous_position[$i]->position;
    }
    //$periodForKeysFrom isset
    if($periodForKeysFrom){
        unset($dates);
        unset($positions);
        for($i=0; $i<=$quan; $i++){
            $stamp_from = DateTime::createFromFormat("dmY", $periodForKeysFrom)->getTimestamp();
            $stamp_date = DateTime::createFromFormat("dmY", date("dmY", $model->previous_position[$i]->fullDate))->getTimestamp();
            if($stamp_date >= $stamp_from){
                $dates[$i] = date("F j, Y, g:i a", $model->previous_position[$i]->fullDate);
                $positions[$i] = $model->previous_position[$i]->position;
            }
        }
    }
    //$periodForKeysTill isset
    if($periodForKeysTill){
        unset($dates);
        unset($positions);
        for($i=0; $i<=$quan; $i++){
            $stamp_till = DateTime::createFromFormat("dmY", $periodForKeysTill)->getTimestamp();
            $stamp_date = DateTime::createFromFormat("dmY", date("dmY", $model->previous_position[$i]->fullDate))->getTimestamp();
            if($stamp_date <= $stamp_till){
                $dates[$i] = date("F j, Y, g:i a", $model->previous_position[$i]->fullDate);
                $positions[$i] = $model->previous_position[$i]->position;
            }
        }
    }
    // both periods are defined
    if($periodForKeysFrom and $periodForKeysTill){
        unset($dates);
        unset($positions);
        for($i=0; $i<=$quan; $i++){
            $stamp_from = DateTime::createFromFormat("dmY", $periodForKeysFrom)->getTimestamp();
            $stamp_till = DateTime::createFromFormat("dmY", $periodForKeysTill)->getTimestamp();
            $stamp_date = DateTime::createFromFormat("dmY", date("dmY", $model->previous_position[$i]->fullDate))->getTimestamp();
            if($stamp_date <= $stamp_till and $stamp_date >= $stamp_from){
                $dates[$i] = date("F j, Y, g:i a", $model->previous_position[$i]->fullDate);
                $positions[$i] = $model->previous_position[$i]->position;
            }
        }
    }
    //updating indexes
    $dates = array_values($dates);
    $positions = array_values($positions);
?>

    <?= Html::a(Yii::t('app', 'Экспорт в XLS'), ['/keys/excel-key', 'key_id' => Yii::$app->request->get('id')], ['class'=>'btn btn-primary']) ?>


    <?php $form = ActiveForm::begin(); ?>

        <label><?= Yii::t('app', 'Начальная дата'); ?></label>
        <?= DateRangePicker::widget([
            'name'=>'periodForKeysFrom',
            'convertFormat'=>true,
            'pluginOptions'=>[
                'timePicker'=>false,
                'timePickerIncrement'=>15,
                'locale'=>['format' => 'Y-m-d'],
                'singleDatePicker'=>true,
                'showDropdowns'=>true
            ]
        ]); ?>

        <label><?= Yii::t('app', 'Конечная дата'); ?></label>
        <?= DateRangePicker::widget([
            'name'=>'periodForKeysTill',
            'convertFormat'=>true,
            'pluginOptions'=>[
                'timePicker'=>false,
                'timePickerIncrement'=>15,
                'locale'=>['format' => 'Y-m-d'],
                'singleDatePicker'=>true,
                'showDropdowns'=>true
            ]
        ]); ?>

        <div class="form-group">
            <?= Html::submitButton( Yii::t('app', 'Применить'), ['class' => 'btn btn-primary']) ?>
        </div>

    <?php $form = ActiveForm::end(); ?>

    <?php if($periodForKeysFrom || $periodForKeysTill) : ?>
        <div><?= Yii::t('app', 'Выбранный период') ?>
            <?php if($periodForKeysFrom) : ?>
                <?= Yii::t('app', 'с') ?>
                <?= DateTime::createFromFormat('dmY', $periodForKeysFrom)->format('d-m-Y') ?>
            <?php endif; ?>
            <?php if($periodForKeysTill) : ?>
                <?= Yii::t('app', 'по') ?>
                <?= DateTime::createFromFormat('dmY', $periodForKeysTill)->format('d-m-Y') ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?= Highcharts::widget([
        'options' => [
            'title' => ['text' => Yii::t('app', 'Динамика')],
            'xAxis' => [
                'categories' => $dates,
            ],
            'yAxis' => [
                'title' => ['text' => Yii::t('app', 'Позиция')]
            ],
            'series' => [
                ['name' => $this->title, 'data' => $positions],

            ]
        ]
    ]); ?>


</div>
