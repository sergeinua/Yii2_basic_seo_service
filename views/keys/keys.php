<?php

/**
 * @var $isNewRecord bool
 * @var $mode KeysForm
 */

use app\models\KeysForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Groups;
use yii\helpers\ArrayHelper;

?>

<div class="keys-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= ($isNewRecord)?'':$form->field($model, 'id', ['template' => '{input}'])->hiddenInput(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'group_id')->dropDownList(ArrayHelper::map(Groups::find()->all(), 'id', 'title'),[
        'prompt' => 'Выберите группу',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($isNewRecord ? 'Create' : 'Update', ['class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>



</div>
