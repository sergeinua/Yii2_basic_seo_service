<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

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
            ]
        ],
    ]) ?>

    <h2>Группы ключевых слов проекта</h2>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Actions</th>
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





</div>
