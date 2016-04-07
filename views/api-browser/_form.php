<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ApiBrowser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="api-browser-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pageviews')->textInput() ?>

    <?= $form->field($model, 'visits')->textInput() ?>

    <?= $form->field($model, 'browser')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'browserVersion')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
