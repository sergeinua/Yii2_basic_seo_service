<?php

use yii\helpers\Html;

?>

<?php if(!Yii::$app->request->get('country')) :
        $country = [];
        $visits = [];
        $i = 0;
        foreach($api_country as $item) :
            $country[$i] = $item->country_iso;
            $visits[$i] = $item->visits;
            $i++;
        endforeach; ?>
        <table class='table table-striped table-hover'>
            <thead>
            <tr>
                <th><?= Yii::t('app', 'Страна'); ?></th>
                <th><?= Yii::t('app', 'Пользователей'); ?></th>
                <th>%</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <?php for($i=0; $i<count($country); $i++) : ?>
            <tr>
                <td>
                    <?= Html::a('<span>' . $country[$i] . '</span>', ['/projects/show-analytics',
                        'id' => Yii::$app->request->get('id'),
                        'country' => $country[$i],
                        'periodForProjectFrom' => Yii::$app->getRequest()->post('periodForProjectFrom') ? Yii::$app->getRequest()->post('periodForProjectFrom') : null,
                        'periodForProjectTill' => Yii::$app->getRequest()->post('periodForProjectTill') ? Yii::$app->getRequest()->post('periodForProjectTill') : null,
                    ]) ?>
                </td>
                <td><?= $visits[$i]; ?></td>
                <td><?= round($visits[$i] / array_sum($visits) * 100, 2) ?></td>
            </tr>
            <?php endfor; ?>

            </tr>
            </tbody>
        </table>
<?php else : ?>
    <?php $total = 0;
    foreach ($api_city as $city) :
        $total += $city['visits'];
    endforeach; ?>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th><?= Yii::t('app', 'Город'); ?></th>
            <th><?= Yii::t('app', 'Пользователей'); ?></th>
            <th>%</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($api_city as $city) { ?>
            <tr>
                <td><?= $city['city_id'] == '' ? '-' : $city['city_id'] ?></td>
                <td><?= $city['visits'] ?></td>
                <td><?= round($city['visits'] / $total * 100, 2) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

<?php endif; ?>