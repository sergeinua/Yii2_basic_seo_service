<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GroupKeySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Group Keys';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="group-key-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Group Key', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'group_id',
            'key_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
