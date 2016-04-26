<?php
use miloschuman\highcharts\Highcharts;
use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\base\View;
use yii\helpers\Html;
use app\models\Projects;
use app\models\ApiSource;

?>

<?php //gapi data last
date_default_timezone_set('Europe/Kiev');
$last_modified = ApiSource::find()
    ->where(['project_id' => Yii::$app->request->get('id')])
    ->orderBy('date asc')
    ->one()['date'];
?>

<div>
    <h1><?= Projects::find()->where(['id' => Yii::$app->request->get('id')])->one()->title; ?></h1>
    <h2><?= Yii::t('app', 'Динамика проекта'); ?></h2>

    <?php if($last_modified) : ?>
        <div>
            <?= Yii::t('app', 'Последнее обновление: '); ?>
            <?= DateTime::createFromFormat('U', $last_modified)->format('Y-m-d h:i:s'); ?>
        </div>
    <?php endif; ?>

    <?= Html::a(Yii::t('app', 'Назад'), Yii::$app->request->referrer, ['class' => 'btn btn-primary']); ?>

    <?= Html::a(Yii::t('app', 'Обновить данные аналитики'), ['/projects/update-analytics-data', 'project_id' => Yii::$app->request->get('id')], ['class'=>'btn btn-primary']) ?>

    <?php $form = ActiveForm::begin(); ?>

    <label><?= Yii::t('app', 'Начальная дата'); ?></label>
    <?= DateRangePicker::widget([
        'name'=>'period_for_project_from',
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
        'name'=>'period_for_project_till',
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

    <?php if($period_from || $period_till) : ?>
        <div><?= Yii::t('app', 'Выбран период') ?>
            <?php if($period_from) : ?>
                <?= Yii::t('app', 'с') ?>
                <?= DateTime::createFromFormat('dmY', $period_from)->format('d-m-Y') ?>
            <?php endif; ?>
            <?php if($period_till) : ?>
                <?= Yii::t('app', 'по') ?>
                <?= DateTime::createFromFormat('dmY', $period_till)->format('d-m-Y') ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if($project_vis_model) : ?>
        <?php //project's visibility
        $dates=[];
        $visibility=[];
        for($i=0; $i<count($project_vis_model); $i++){
            $dates[$i] = date($project_vis_model[$i]['date']);
            $visibility[$i] = $project_vis_model[$i]['visibility'];
        }
        for($i=0; $i<count($dates); $i++) {
            $dates[$i] = DateTime::createFromFormat('Ymd', $dates[$i])->format('d-m-Y');
        };
        $project_model = Projects::find()->where(['id' => Yii::$app->request->get('id')])->one(); ?>

        <!-- // it's not needed right now, but left here - just in case someone gonna need it
        <--?=Highcharts::widget([
            'options' => [
                'title' => ['text' => Yii::t('app', 'Видимость ключевых слов проекта')],
                'xAxis' => [
                    'categories' => $dates,
                ],
                'yAxis' => [
                    'title' => ['text' => Yii::t('app', 'Видимость %')]
                ],
                'series' => [
                    [
                        'name' => $project_model->title,
                        'data' => $visibility
                    ],
                ]
            ]
        ]); ?-->
    <?php endif; ?>
</div>

<?php if($api_source) : ?>

    <?php //sources
    $visits = [];
    $sources = [];
    $i = 0;
    foreach($api_source as $item) :
        $visits[$i] = $item->visits;
        $sources[$i] = $item->source;
        $i++;
    endforeach; ?>

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
        ]); ?>
    </div>

<?php endif; ?>

<?php if($api_browser) : ?>

    <?php //browsers
    $visits = [];
    $browsers = [];
    $i = 0;
    foreach($api_browser as $item) :
        $visits[$i] = $item->visits;
        $browsers[$i] = $item->browser . '-' . $item->browserVersion;
        $i++;
    endforeach; ?>

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

<?php endif; ?>

<?php if($api_os) : ?>

    <?php //os
    $visits = [];
    $os = [];
    $i = 0;
    foreach($api_os as $item) :
        $visits[$i] = $item->visits;
        $os[$i] = $item->os;
        $i++;
    endforeach; ?>

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

<?php endif; ?>

<?php if($api_device) : ?>

    <?php //brands
    $visits = [];
    $brands = [];
    $i = 0;
    foreach($api_device as $item) :
        $visits[$i] = $item->visits;
        $brands[$i] = $item->brand;
        $i++;
    endforeach; ?>

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

<?php endif; ?>

<?php if($api_users && $api_sessions) : ?>

    <?php //users & sessions
    $users = 0;
    $sessions = 0;
    $new_users = 0;
    foreach($api_users as $item) :
        $users += $item->users;
        $new_users += $item->new_users;
        $sessions += $item->session_count;
    endforeach;
    $session_duration = 0;
    $page_views = 0;
    $bounce_rate = 0;
    foreach($api_sessions as $item) :
        $session_duration += $item->session_duration;
        $page_views += $item->pageviews;
        $bounce_rate += $item->bounces;
    endforeach;
    if($bounce_rate)
        $bounce_rate = $bounce_rate / $users * 100; ?>

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
            <td><?= round($bounce_rate, 2); ?> %</td>

        </tr>
        </tbody>
    </table>

<?php endif; ?>

<?php if($api_country || $api_city) : ?>

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

<?php endif; ?>