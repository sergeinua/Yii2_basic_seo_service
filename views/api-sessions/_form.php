<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ApiSessions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="api-sessions-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'session_duration')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pageviews')->textInput() ?>

    <?= $form->field($model, 'bounces')->textInput() ?>

    <?= $form->field($model, 'session_duration_bucket')->textInput() ?>

    <?= $form->field($model, 'project_id')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
