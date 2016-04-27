<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use miloschuman\highcharts\Highcharts;
use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\base\View;

/* @var $this yii\web\View */
/* @var $model app\models\Projects */

$this->title = $model->title;
//$this->params['breadcrumbs'][] = ['label' => 'Projects', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
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
            'gapi_profile_id',
            'language',
            [
                'attribute' => 'upd_period',
                'value' => $model->upd_period / 86400
            ],
            [
                'attribute' => 'upd_period',
                'label' => Yii::t('app', 'ТИЦ'),
                'value' => 'http://www.yandex.ru/cycounter?reclamare.ua',
                'format' => ['image',['width'=>'88','height'=>'31']],
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

        <?php foreach ($model->groups as $group) : ?>
            <tr>
                <td><?= $group->id ?></td>
                <td><?= Html::a('<span>' . $group->title . '</span>', ['/groups/view', 'id' => $group->id]) ?></td>
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
        <?php endforeach; ?>

        </tbody>
    </table>

</div>
