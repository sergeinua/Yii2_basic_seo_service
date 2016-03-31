<?php


$lng = [];
$visits = [];
$i = 0;
foreach($api_lng as $item) :
    $lng[$i] = $item->getDimensions()['language'];
    $visits[$i] = $item->getMetrics()['visits'];
    $i++;
endforeach;
$lng = array_reverse($lng);
$visits = array_reverse($visits);
?>

<h3><?= Yii::t('app', 'Язык'); ?></h3>
<table class='table table-striped table-hover'>
    <thead>
    <tr>
        <th><?= Yii::t('app', 'Язык'); ?></th>
        <th><?= Yii::t('app', 'Пользователи'); ?></th>
        <th>%</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <?php for($i=0; $i<count($lng); $i++) : ?>
    <tr>
        <td><?= $lng[$i]; ?></td>
        <td><?= $visits[$i]; ?></td>
        <td><?= round($visits[$i] / array_sum($visits) * 100, 2) ?></td>
    </tr>
    <?php endfor; ?>

    </tr>
    </tbody>
</table>