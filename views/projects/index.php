<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Projects';
//$this->params['breadcrumbs'][] = $this->title;
?>
<?php if($dataProvider && $searchModel) :  ?>
    <div class="projects-index">

        <h1><?= Yii::t('app', 'Проекты') ?></h1>
        <?php  //echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a(Yii::t('app', 'Создать'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'title',
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a('<span>' . $model->title . '</span>', ['/projects/view', 'id' => $model->id]);
                    }
                ],
                'description',
                [
                    'attribute' => 'status',
                    'value' => function ($model){
                        return $model->status == 0 ? Yii::t('app', 'Неактивно') : Yii::t('app', 'Активно');
                    }
                ],

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>

    </div>
<?php else : ?>
    <span><?= Yii::t('app', 'у Вас нет проектов'); ?></span>
<?php endif; ?>