<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProdvigatorOrganicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Prodvigator Organics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prodvigator-organic-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Prodvigator Organic', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'region_queries_count',
            'domain',
            'keyword',
            'url:url',
            // 'right_spell',
            // 'dynamic',
            // 'found_results',
            // 'url_crc:url',
            // 'cost',
            // 'concurrency',
            // 'position',
            // 'date',
            // 'keyword_id',
            // 'subdomain',
            // 'region_queries_count_wide',
            // 'types',
            // 'geo_names',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
