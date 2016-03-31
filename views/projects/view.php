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


    <?php //project's visibility
    $dates=[];
    $visibility=[];
    for($i=0; $i<count($project_vis_model); $i++){
        $dates[$i] = date($project_vis_model[$i]['date']);
        $visibility[$i] = $project_vis_model[$i]['visibility'];
    }
    for($i=0; $i<count($dates); $i++) {
        $dates[$i] = DateTime::createFromFormat('dmY', $dates[$i])->format('d-m-Y');
    }; ?>
        <?= Highcharts::widget([
            'options' => [
                'title' => ['text' => Yii::t('app', 'Видимость ключевых слов проекта')],
                'xAxis' => [
                    'categories' => $dates,
                ],
                'yAxis' => [
                    'title' => ['text' => Yii::t('app', 'Видимость %')]
                ],
                'series' => [
                    ['name' => $this->title, 'data' => $visibility],
                ]
            ]
        ]); ?>

    </div>

    <?php //sources
    $visits=[];
    $sources=[];
    foreach($api_source as $item) :
        $visits[$i] = $item->getMetrics()['visits'];
        $sources[$i] = $item->getDimensions()['source'];
        $i++;
    endforeach;
    $visits = array_reverse($visits);
    $sources = array_reverse($sources); ?>

    <div>
        <?= Highcharts::widget([
            'scripts' => [
                'highcharts-more',
            ],
            'options' => [
                'chart' => [
                    'type' => 'bar',
                    'inverted' => false,
                ],
                'title' => ['text' => Yii::t('app', 'Источники трафика')],
                'xAxis' => [
                    'categories' => $sources,
                ],
                'yAxis' => [
                    'title' => ['text' => Yii::t('app', 'Количество')]
                ],
                'legend' => [
                    'enabled' => false
                ],
                'series' => [[
                    'name' => Yii::t('app', 'Переходов'),
                    'data' => $visits,
                ]]
            ]
        ]);?>
    </div>

    <?php //browsers
    $visits=[];
    $browsers=[];
    foreach($api_browser as $item) :
        //setting quantity of the displayed results
        if($i>(count($api_browser)-20)) {
            $visits[$i] = $item->getMetrics()['visits'];
            $browsers[$i] = $item->getDimensions()['browser'] . '-' . $item->getDimensions()['browserVersion'];
        }
        $i++;
    endforeach;
    $visits = array_reverse($visits);
    $browsers = array_reverse($browsers); ?>

    <div>
        <?= Highcharts::widget([
            'scripts' => [
                'highcharts-more',
            ],
            'options' => [
                'chart' => [
                    'type' => 'bar',
                    'inverted' => false,
                ],
                'title' => ['text' => Yii::t('app', 'Браузеры')],
                'xAxis' => [
                    'categories' => $browsers,
                ],
                'yAxis' => [
                    'title' => ['text' => Yii::t('app', 'Количество')]
                ],
                'legend' => [
                    'enabled' => false
                ],
                'series' => [[
                    'name' => Yii::t('app', 'Количество'),
                    'data' => $visits,
                ]]
            ]
        ]); ?>
    </div>

    <?php //os
    $visits=[];
    $os=[];
    foreach($api_os as $item) :
        $visits[$i] = $item->getMetrics()['visits'];
        $os[$i] = $item->getDimensions()['operatingSystem'];
        $i++;
    endforeach;
    $visits = array_reverse($visits);
    $os = array_reverse($os); ?>

    <div>
        <?= Highcharts::widget([
            'scripts' => [
                'highcharts-more',
            ],
            'options' => [
                'chart' => [
                    'type' => 'column',
                    'inverted' => false,
                ],
                'title' => ['text' => Yii::t('app', 'ОС')],
                'xAxis' => [
                    'categories' => $os,
                ],
                'yAxis' => [
                    'title' => ['text' => Yii::t('app', 'Количество')]
                ],
                'legend' => [
                    'enabled' => false
                ],
                'series' => [[
                    'name' => Yii::t('app', 'Количество'),
                    'data' => $visits,
                ]]
            ]
        ]); ?>
    </div>

    <?php //brands
    $visits=[];
    $brands=[];
    foreach($api_device as $item) :
        $visits[$i] = $item->getMetrics()['visits'];
        $brands[$i] = $item->getDimensions()['mobileDeviceBranding'];
        $i++;
    endforeach;
    $visits = array_reverse($visits);
    $brands = array_reverse($brands); ?>

    <div>
        <?= Highcharts::widget([
            'scripts' => [
                'highcharts-more',
            ],
            'options' => [
                'chart' => [
                    'type' => 'column',
                    'inverted' => false,
                ],
                'title' => ['text' => Yii::t('app', 'Устройства')],
                'xAxis' => [
                    'categories' => $brands,
                ],
                'yAxis' => [
                    'title' => ['text' => Yii::t('app', 'Количество')]
                ],
                'legend' => [
                    'enabled' => false
                ],
                'series' => [[
                    'name' => Yii::t('app', 'Количество'),
                    'data' => $visits,
                ]]
            ]
        ]);?>
    </div>


    <?php //users & sessions
    $users = 0;
    $sessions = 0;
    $new_users = 0;
    foreach($api_users as $item) :
        $users += $item->getMetrics()['users'];
        $new_users += $item->getMetrics()['newUsers'];
        $sessions += $item->getDimensions()['sessionCount'];
    endforeach;
    $session_duration = 0;
    $page_views = 0;
    $bounce_rate = 0;
    foreach($api_sessions as $item) :
        $session_duration += $item->getMetrics()['sessionDuration'];
        $page_views += $item->getMetrics()['pageviews'];
        $bounce_rate += $item->getMetrics()['bounceRate'];
    endforeach;
    $bounce_rate = $bounce_rate / count($api_sessions) * 100; ?>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><?= Yii::t('app', 'Сеансы'); ?></th>
                <th><?= Yii::t('app', 'Пользователи'); ?></th>
                <th><?= Yii::t('app', 'Просмотры страниц'); ?></th>
                <th><?= Yii::t('app', 'Новые пользователи'); ?></th>
                <th><?= Yii::t('app', 'Страниц/сеанс'); ?></th>
                <th><?= Yii::t('app', 'Показатель отказов'); ?></th>

            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $users; ?></td>
                <td><?= $sessions; ?></td>
                <td><?= $page_views; ?></td>
                <td><?= round($new_users / $users * 100); ?> %</td>
                <td><?= round($page_views / $users, 2); ?></td>
                <td><?= $bounce_rate; ?></td>

            </tr>
        </tbody>
    </table>


    <?= Tabs::widget([
        'items' => [
            [
                'label' => ($api_city == null) ? Yii::t('app', 'Страны') : Yii::t('app', 'Города'),
                'content' => View::render('_second_tab', [
                    'api_country' => $api_country,
                    'api_city' => $api_city,
                ]),

                'active' => true,
            ],
            [
                'label' => Yii::t('app', 'Язык'),
                'content' => View::render('_first_tab', ['api_lng' => $api_lng]),
            ],
        ]]); ?>












</div>
