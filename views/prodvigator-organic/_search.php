<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProdvigatorOrganicSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prodvigator-organic-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'region_queries_count') ?>

    <?= $form->field($model, 'domain') ?>

    <?= $form->field($model, 'keyword') ?>

    <?= $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'right_spell') ?>

    <?php // echo $form->field($model, 'dynamic') ?>

    <?php // echo $form->field($model, 'found_results') ?>

    <?php // echo $form->field($model, 'url_crc') ?>

    <?php // echo $form->field($model, 'cost') ?>

    <?php // echo $form->field($model, 'concurrency') ?>

    <?php // echo $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'keyword_id') ?>

    <?php // echo $form->field($model, 'subdomain') ?>

    <?php // echo $form->field($model, 'region_queries_count_wide') ?>

    <?php // echo $form->field($model, 'types') ?>

    <?php // echo $form->field($model, 'geo_names') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
