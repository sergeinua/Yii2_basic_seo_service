<?php

use miloschuman\highcharts\Highcharts;
use yii\bootstrap\Html;
use kartik\daterange\DateRangePicker;
use yii\bootstrap\ActiveForm;
use app\models\ProdvigatorData;

?>

<?php
/**
 * visibility data
 */

$vis_dates = [];
$vis_quan = [];
$vis_words = [];
$i = 0;
foreach($model as $item) :
    $vis_dates[$i] = $item->date;
    $vis_quan[$i] = $item->visible;
    $vis_words[$i] = $item->keywords;
    $i++;
endforeach;

$periodFrom = null;
$periodTill = null;
if(Yii::$app->request->post('dateFrom'))
    $periodFrom = Yii::$app->request->post('dateFrom');
if(Yii::$app->request->post('dateTill'))
    $periodTill = Yii::$app->request->post('dateTill');



?>

    <?php if($periodFrom || $periodTill) : ?>
        <div><?= Yii::t('app', 'Выбран период'); ?>
            <?php if($periodFrom) : ?>
                <?= Yii::t('app', 'с'); ?>
                <?= $periodFrom; ?>
            <?php endif; ?>
            <?php if($periodTill) : ?>
                <?= Yii::t('app', 'по'); ?>
                <?= $periodTill; ?>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <?= Yii::t('app', 'Выбран период c: '); ?>
        <?= date('Y-m-d', strtotime('-6 months')); ?>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(); ?>

        <label><?= Yii::t('app', 'Начальная дата'); ?></label>
        <?= DateRangePicker::widget([
            'name'=>'dateFrom',
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
            'name'=>'dateTill',
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


<?= Html::a(Yii::t('app', 'Обновить данные продвигатора'), ['/projects/update-prodvigator', 'project_id' => Yii::$app->request->get('project_id')], ['class'=>'btn btn-primary']) ?>


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
            'title' => ['text' => Yii::t('app', 'История изменения видимости')],
            'xAxis' => [
                'categories' => $vis_dates,
            ],
            'yAxis' => [
                'title' => ['text' => Yii::t('app', 'Видимость')]
            ],
            'legend' => [
                'enabled' => false
            ],
            'series' => [[
                'name' => Yii::t('app', 'Видимость'),
                'data' => $vis_quan,
            ]]
        ]
    ]);?>
</div>

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
            'title' => ['text' => Yii::t('app', 'История изменения количества фраз')],
            'xAxis' => [
                'categories' => $vis_dates,
            ],
            'yAxis' => [
                'title' => ['text' => Yii::t('app', 'Количество фраз')]
            ],
            'legend' => [
                'enabled' => false
            ],
            'series' => [[
                'name' => Yii::t('app', 'Количество фраз'),
                'data' => $vis_words,
            ]]
        ]
    ]);?>
</div>

    <?= Html::a(Yii::$app->request->get('show_organic') ? Yii::t('app', 'Назад') : Yii::t('app', 'Поисковые запросы в органическом поиске'),
        ['/projects/show-prodvigator',
            'project_id' => Yii::$app->request->get('project_id'),
            'show_organic' => Yii::$app->request->get('show_organic') ? null : 1,
        ], ['class'=>'btn btn-primary']); ?>

<table class="table table-striped table-hover">
    <thead>
    <tr>
        <th><?= Yii::t('app', 'Всего'); ?></th>
        <th><?= Yii::t('app', 'Новых'); ?></th>
        <th><?= Yii::t('app', 'Потерянных'); ?></th>
        <th><?= Yii::t('app', 'Выросших'); ?></th>
        <th><?= Yii::t('app', 'Упавших'); ?></th>
        <th><?= Yii::t('app', 'Суммарный трафик'); ?></th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= $model[0]->keywords; ?></td>
            <td><?= $model[0]->new_keywords; ?></td>
            <td><?= $model[0]->out_keywords; ?></td>
            <td><?= $model[0]->rised_keywords; ?></td>
            <td><?= $model[0]->down_keywords; ?></td>
            <td><?= $model[0]->traff; ?></td>
        </tr>
    </tbody>
</table>



<?php if(Yii::$app->request->get('show_organic')) : ?>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><?= Yii::t('app', 'Ключевое слово'); ?></th>
                <th><?= Yii::t('app', 'Позиция'); ?></th>
                <th><?= Yii::t('app', 'Количество запросов'); ?></th>
                <th><?= Yii::t('app', 'Стоимость $'); ?></th>
                <th><?= Yii::t('app', 'Конкуренция в PPС'); ?></th>
                <th><?= Yii::t('app', 'Результатов'); ?></th>
                <th><?= Yii::t('app', 'URL'); ?></th>

            </tr>
        </thead>
        <tbody>
        <?php foreach($model_organic as $item) : ?>
            <tr>
                <td><?= $item->keyword; ?></td>
                <td><?= $item->position; ?></td>
                <td><?= $item->region_queries_count; ?></td>
                <td><?= $item->cost; ?></td>
                <td><?= $item->concurrency; ?></td>
                <td><?= $item->found_results; ?></td>
                <td><?= $item->url; ?></td>

            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>