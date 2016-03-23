<?php

use yii\helpers\Html;
use yii\grid\GridView;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */
/* @var $searchModel app\models\KeysSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Позиции ключевых слов');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php date_default_timezone_set('Europe/Kiev'); ?>

<div class="keys-index">
    <h1><?= Yii::t('app', 'Позиции ключевых слов')?></h1>
    <div class="table-styles">
        <p><?= $model[0]->group->project->title; ?></p>
        <p><?= $model[0]->group->title; ?></p>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
<!--                <th>--><?//= Yii::t('app', 'Проект')?><!--</th>-->
<!--                <th>--><?//= Yii::t('app', 'Группа')?><!--</th>-->
                <th><?= Yii::t('app', 'Ключевое слово')?></th>
                <th><?= Yii::t('app', 'Последнее обновление')?></th>
                <th><?= Yii::t('app', 'Позиция')?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($model as $item){ ?>
                <tr>
<!--                    <td>--><?//= isset($item->group->project->title) ? $item->group->project->title : '-'; ?><!--</td>-->
<!--                    <td>--><?//= isset($item->group->title) ? $item->group->title : '-'; ?><!--</td>-->
                    <td><?= isset($item->title) ? $item->title : '-'; ?></td>
                    <td><?= isset($item->fullDate) ? date('F j, Y, g:i a', $item->fullDate) : '-'; ?></td>
                    <td><?= isset($item->position) ? $item->position : '-'; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
