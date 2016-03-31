<?php

use yii\helpers\Html;

?>



    <?php if(!Yii::$app->request->get('country')) :
            $country = [];
            $visits = [];
            $i = 0;
            foreach($api_country as $item) :
                $country[$i] = $item->getDimensions()['countryIsoCode'];
                $visits[$i] = $item->getMetrics()['visits'];
                $i++;
            endforeach;
            $country = array_reverse($country);
            $visits = array_reverse($visits);
            ?>
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
                        <?= Html::a('<span>' . $country[$i] . '</span>', ['/projects/view',
                            'id' => Yii::$app->request->get('id'),
                            'country' => $country[$i],
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