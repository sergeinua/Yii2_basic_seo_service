<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Groups;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Keys */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="keys-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'group_id')->dropDownList(ArrayHelper::map(Groups::find()->all(), 'id', 'title'),[
        'prompt' => 'Выберите группу',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>



</div>
