<?php

use miloschuman\highcharts\Highcharts;


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

?>




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
