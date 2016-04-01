<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProdvigatorData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prodvigator-data-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keywords')->textInput() ?>

    <?= $form->field($model, 'traff')->textInput() ?>

    <?= $form->field($model, 'new_keywords')->textInput() ?>

    <?= $form->field($model, 'out_keywords')->textInput() ?>

    <?= $form->field($model, 'rised_keywords')->textInput() ?>

    <?= $form->field($model, 'down_keywords')->textInput() ?>

    <?= $form->field($model, 'visible')->textInput() ?>

    <?= $form->field($model, 'cost_min')->textInput() ?>

    <?= $form->field($model, 'cost_max')->textInput() ?>

    <?= $form->field($model, 'ad_keywords')->textInput() ?>

    <?= $form->field($model, 'ads')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
