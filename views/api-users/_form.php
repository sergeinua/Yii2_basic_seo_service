<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ApiUsers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="api-users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'users')->textInput() ?>

    <?= $form->field($model, 'new_users')->textInput() ?>

    <?= $form->field($model, 'session_count')->textInput() ?>

    <?= $form->field($model, 'project_id')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>