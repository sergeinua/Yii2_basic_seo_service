<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProdvigatorDataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Prodvigator Datas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prodvigator-data-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Prodvigator Data', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'domain',
            'keywords',
            'traff',
            'new_keywords',
            // 'out_keywords',
            // 'rised_keywords',
            // 'down_keywords',
            // 'visible',
            // 'cost_min',
            // 'cost_max',
            // 'ad_keywords',
            // 'ads',
            // 'date',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
