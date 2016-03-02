<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\GroupKey;
use yii\data\ActiveDataProvider;
use app\models\Keys;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Groups */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="groups-view">

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

    <h2>Ключевые слова группы</h2>

    <?= Html::a(Yii::t('app', 'Добавить ключевое слово'), ['/keys/create', 'group_id' => Yii::$app->request->get('id')], ['class'=>'btn btn-primary']) ?>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($model->keys as $key) { ?>

            <tr>
                <td><?= $key->id ?></td>
                <td><?= $key->title ?></td>
                <td>
                    <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/keys/view', 'id' => $key->id]) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/keys/update', 'id' => $key->id]) ?>
                    <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['/keys/delete', 'id' => $key->id], [
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
