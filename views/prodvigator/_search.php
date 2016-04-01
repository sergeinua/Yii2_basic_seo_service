<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProdvigatorDataSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prodvigator-data-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'domain') ?>

    <?= $form->field($model, 'keywords') ?>

    <?= $form->field($model, 'traff') ?>

    <?= $form->field($model, 'new_keywords') ?>

    <?php // echo $form->field($model, 'out_keywords') ?>

    <?php // echo $form->field($model, 'rised_keywords') ?>

    <?php // echo $form->field($model, 'down_keywords') ?>

    <?php // echo $form->field($model, 'visible') ?>

    <?php // echo $form->field($model, 'cost_min') ?>

    <?php // echo $form->field($model, 'cost_max') ?>

    <?php // echo $form->field($model, 'ad_keywords') ?>

    <?php // echo $form->field($model, 'ads') ?>

    <?php // echo $form->field($model, 'date') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
