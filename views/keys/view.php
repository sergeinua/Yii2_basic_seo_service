<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use miloschuman\highcharts\Highcharts;
use kartik\daterange\DateRangePicker;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Keys */

$this->title = $model->title;
//$this->params['breadcrumbs'][] = ['label' => 'Keys', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="keys-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Назад'), Yii::$app->request->referrer, ['class' => 'btn btn-primary']); ?>
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
    ]); ?>

    <?php // applying the correct timezone
    date_default_timezone_set('Europe/Kiev');
    $quan = count($model->previousPosition) - 1;
    //period is not defined
    for($i=0; $i<=$quan; $i++){
        $dates[$i] = date("F j, Y, g:i a", $model->previousPosition[$i]->fullDate);
        $positions[$i] = $model->previousPosition[$i]->position;
    }
    //$period_for_keys_from by default is showing last 30 days
    if(!$period_for_keys_from) :
        $period_for_keys_from = strtotime('-1 month', date('U'));
        $period_for_keys_from = date('dmY', $period_for_keys_from);
    endif;
    //$period_for_keys_from isset
    if($period_for_keys_from){
        unset($dates);
        unset($positions);
        for($i=0; $i<=$quan; $i++){
            $stamp_from = DateTime::createFromFormat("dmY", $period_for_keys_from)->getTimestamp();
            $stamp_date = DateTime::createFromFormat("dmY", date("dmY", $model->previousPosition[$i]->fullDate))->getTimestamp();
            if($stamp_date >= $stamp_from){
                $dates[$i] = date("F j, Y, g:i a", $model->previousPosition[$i]->fullDate);
                $positions[$i] = $model->previousPosition[$i]->position;
            }
        }
    }
    //$period_for_keys_till isset
    if($period_for_keys_till){
        unset($dates);
        unset($positions);
        for($i=0; $i<=$quan; $i++){
            $stamp_till = DateTime::createFromFormat("dmY", $period_for_keys_till)->getTimestamp();
            $stamp_date = DateTime::createFromFormat("dmY", date("dmY", $model->previousPosition[$i]->fullDate))->getTimestamp();
            if($stamp_date <= $stamp_till){
                $dates[$i] = date("F j, Y, g:i a", $model->previousPosition[$i]->fullDate);
                $positions[$i] = $model->previousPosition[$i]->position;
            }
        }
    }
    // both periods are defined
    if($period_for_keys_from and $period_for_keys_till){
        unset($dates);
        unset($positions);
        for($i=0; $i<=$quan; $i++){
            $stamp_from = DateTime::createFromFormat("dmY", $period_for_keys_from)->getTimestamp();
            $stamp_till = DateTime::createFromFormat("dmY", $period_for_keys_till)->getTimestamp();
            $stamp_date = DateTime::createFromFormat("dmY", date("dmY", $model->previousPosition[$i]->fullDate))->getTimestamp();
            if($stamp_date <= $stamp_till and $stamp_date >= $stamp_from){
                $dates[$i] = date("F j, Y, g:i a", $model->previousPosition[$i]->fullDate);
                $positions[$i] = $model->previousPosition[$i]->position;
            }
        }
    }
    if(isset($dates)) :
        //updating indexes
        $dates = array_values($dates);
        $positions = array_values($positions);
    endif;
    // setting dates the needed way
    $dates = array_reverse($dates);
    $positions = array_reverse($positions); ?>

    <?= Html::a(Yii::t('app', 'Экспорт в XLS'), ['/keys/excel-key',
        'key_id' => Yii::$app->request->get('id'),
        'period_for_keys_from' => $period_for_keys_from,
        'period_for_keys_till' => $period_for_keys_till,
    ], ['class'=>'btn btn-primary']); ?>

    <?= Html::a(Yii::t('app', 'Экспорт в PDF'), ['/keys/pdf-key',
        'key_id' => Yii::$app->request->get('id'),
        'period_for_keys_from' => $period_for_keys_from,
        'period_for_keys_till' => $period_for_keys_till,
    ], ['class'=>'btn btn-primary']); ?>


    <?php $form = ActiveForm::begin(); ?>

        <label><?= Yii::t('app', 'Начальная дата'); ?></label>
        <?= DateRangePicker::widget([
            'name'=>'period_for_keys_from',
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
            'name'=>'period_for_keys_till',
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

    <?php if($period_for_keys_from || $period_for_keys_till) : ?>
        <div><?= Yii::t('app', 'Выбран период') ?>
            <?php if($period_for_keys_from) : ?>
                <?= Yii::t('app', 'с') ?>
                <?= DateTime::createFromFormat('dmY', $period_for_keys_from)->format('d-m-Y') ?>
            <?php endif; ?>
            <?php if($period_for_keys_till) : ?>
                <?= Yii::t('app', 'по') ?>
                <?= DateTime::createFromFormat('dmY', $period_for_keys_till)->format('d-m-Y') ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if(isset($dates)) : ?>
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
    <?php endif; ?>

</div>
