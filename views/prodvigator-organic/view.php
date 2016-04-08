<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProdvigatorOrganic */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Prodvigator Organics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prodvigator-organic-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            'region_queries_count',
            'domain',
            'keyword',
            'url:url',
            'right_spell',
            'dynamic',
            'found_results',
            'url_crc:url',
            'cost',
            'concurrency',
            'position',
            'date',
            'keyword_id',
            'subdomain',
            'region_queries_count_wide',
            'types',
            'geo_names',
        ],
    ]) ?>

</div>
