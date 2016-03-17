<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\GroupKey;
use yii\data\ActiveDataProvider;
use app\models\Keys;
use yii\helpers\Url;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */
/* @var $model app\models\Groups */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php // applying the correct timezone
    date_default_timezone_set('Europe/Kiev');
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
            ],
            'googlehost',
            'language',
        ],
    ]) ?>

    <h2><?= Yii::t('app', 'Ключевые слова группы'); ?></h2>

    <?= Html::a(Yii::t('app', 'Добавить ключевое слово'), ['/keys/create', 'group_id' => Yii::$app->request->get('id')], ['class'=>'btn btn-primary']) ?>

    <?= Html::a(Yii::t('app', 'Экспорт в XLS'), ['/keys/excel-group', 'group_id' => Yii::$app->request->get('id')], ['class'=>'btn btn-primary']) ?>

    <?= Html::a(Yii::t('app', 'Обновить все ключевые слова группы'), ['/keys/update-all-keys', 'group_id' => Yii::$app->request->get('id')], ['class'=>'btn btn-primary']) ?>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Actions</th>
                <th>Position</th>
                <th>Last updated</th>
                <th>Update</th>
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
                <td>
                    <?php if(isset($key->position->position)) : ?>
                        <?= $key->position->position ?>
                    <?php else : ?>
                        -
                    <?php endif; ?>

                    <?php if(isset($key->previous_position['1']['position'])) : ?>
                        <?php $result = -( (int)$key->position->position - (int)$key->previous_position['1']['position'] );
                        if($result > 0)
                            $result = '+' . $result;
                        ?>
                        ( <?php echo $result; ?> )
                    <?php else : ?>
                        -
                    <?php endif; ?>
                </td>
                <td>
                    <?= isset($key->position->fullDate) ? date("F j, Y, g:i a",  $key->position->fullDate) : ''; ?>
                </td>
                <td>
                    <?= Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['/keys/update-single-key',
                        'key_id' => $key->id,
                        'project_link' => $model->project->title,
                        'group_id' => $model->id,
                    ]) ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <?php
    global $first;
    global $second;
    global $third;
    global $fourth;
    global $fifth;
    global $sixth;
    global $seventh;
    global $eighth;
    global $ninth;

    if($model->keys) {
        foreach ($model->keys as $key) {
            if(isset($key->position->position)) {
                if ($key->position->position <= 3)
                    $first++;
                if ($key->position->position > 3 && $key->position->position <= 10)
                    $second++;
                if ($key->position->position <= 10)
                    $third++;
                if ($key->position->position >= 11 && $key->position->position <= 20)
                    $fourth++;
                if ($key->position->position >= 21 && $key->position->position <= 50)
                    $fifth++;
                if ($key->position->position >= 21 && $key->position->position <= 50)
                    $sixth++;
                if ($key->position->position < 100)
                    $seventh++;
                if ($key->position->position > 100)
                    $eighth++;
            }
        }
    }?>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>1 - 3</th>
                <th>4 - 10</th>
                <th>1 - 10</th>
                <th>11 - 20</th>
                <th>21 - 50</th>
                <th>51 - 100</th>
                <th>< 100</th>
                <th>> 100</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo isset($first) ? $first : '0'; ?></td>
                <td><?php echo isset($second) ? $second : '0'; ?></td>
                <td><?php echo isset($third) ? $third : '0'; ?></td>
                <td><?php echo isset($fourth) ? $fourth : '0'; ?></td>
                <td><?php echo isset($fifth) ? $fifth : '0'; ?></td>
                <td><?php echo isset($sixth) ? $sixth : '0'; ?></td>
                <td><?php echo isset($seventh) ? $seventh : '0'; ?></td>
                <td><?php echo isset($eighth) ? $eighth : '0'; ?></td>
            </tr>
        </tbody>
    </table>

    <div>
        <h2>Key position dynamics</h2>

        <?= Html::a(Yii::t('app', 'Обновить данные'), ['/group-visibility/update_position', 'group_id' => Yii::$app->request->get('id')], ['class'=>'btn btn-primary']) ?>

        <?php
        $i=0;
        $dates=[];
        $visibility=[];
        for($i=0; $i<count($gr_vis_model); $i++){
            $dates[$i] = date($gr_vis_model[$i]['date']);
            $visibility[$i] = $gr_vis_model[$i]['visibility'];
        }?>
        <?php
        // formatting dates
        for($i=0; $i<count($dates); $i++) {
            $dates[$i] = DateTime::createFromFormat('dmY', $dates[$i])->format('d-m-Y');
        }; ?>

        <?= Highcharts::widget([
            'options' => [
                'title' => ['text' => 'Visibility'],
                'xAxis' => [
                    'categories' => $dates,
                ],
                'yAxis' => [
                    'title' => ['text' => 'Percentage']
                ],
                'series' => [
                    ['name' => $this->title, 'data' => $visibility],
                ]
            ]
        ]); ?>
    </div>


</div>
