<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use miloschuman\highcharts\Highcharts;
use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projects-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Обновить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
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
            'description',
            [
                'attribute' => 'status',
                'value' => $model->status == 0 ? Yii::t('app', 'Неактивно') : Yii::t('app', 'Активно'),
            ],
            'googlehost',
            'language',
            [
                'attribute' => 'upd_period',
                'value' => $model->upd_period / 86400
            ],
        ],
    ]) ?>

    <h2><?= Yii::t('app', 'Группы ключевых слов проекта'); ?></h2>

    <?= Html::a(Yii::t('app', 'Добавить группу ключевых слов'), ['/groups/create', 'project_id' => Yii::$app->request->get('id')], ['class'=>'btn btn-primary']) ?>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th><?= Yii::t('app', 'Название'); ?></th>
            <th><?= Yii::t('app', 'Действия'); ?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($model->groups as $group) { ?>

            <tr>
                <td><?= $group->id ?></td>
                <td><?= $group->title ?></td>
                <td>
                    <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/groups/view', 'id' => $group->id]) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/groups/update', 'id' => $group->id]) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['/groups/delete', 'id' => $group->id], [
                        'data' => [
                            'confirm' => 'Are you sure?',
                            'method' => 'post',
                        ]
                    ]) ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>


    <div>
        <h2><?= Yii::t('app', 'Динамика проекта'); ?></h2>

        <?= Html::a(Yii::t('app', 'Обновить данные'), ['/project-visibility/update-position', 'project_id' => Yii::$app->request->get('id')], ['class'=>'btn btn-primary']) ?>

        <?php $form = ActiveForm::begin(); ?>

            <label><?= Yii::t('app', 'Начальная дата'); ?></label>
            <?= DateRangePicker::widget([
                'name'=>'periodForProjectFrom',
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
                'name'=>'periodForProjectTill',
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

        <?php if($periodFrom || $periodTill) : ?>
            <div><?= Yii::t('app', 'Выбранный период') ?>
                <?php if($periodFrom) : ?>
                    <?= Yii::t('app', 'с') ?>
                    <?= DateTime::createFromFormat('dmY', $periodFrom)->format('d-m-Y') ?>
                <?php endif; ?>
                <?php if($periodTill) : ?>
                    <?= Yii::t('app', 'по') ?>
                    <?= DateTime::createFromFormat('dmY', $periodTill)->format('d-m-Y') ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>


    <?php
        $i=0;
        $dates=[];
        $visibility=[];
        for($i=0; $i<count($project_vis_model); $i++){
            $dates[$i] = date($project_vis_model[$i]['date']);
            $visibility[$i] = $project_vis_model[$i]['visibility'];
        }
        for($i=0; $i<count($dates); $i++) {
            $dates[$i] = DateTime::createFromFormat('dmY', $dates[$i])->format('d-m-Y');
        };
    ?>

        <?= Highcharts::widget([
            'options' => [
                'title' => ['text' => Yii::t("app", "Ключевые слова проекта")],
                'xAxis' => [
                    'categories' => $dates,
                ],
                'yAxis' => [
                    'title' => ['text' => Yii::t("app", "Видимость %")]
                ],
                'series' => [
                    ['name' => $this->title, 'data' => $visibility],
                ]
            ]
        ]); ?>

    </div>





</div>
