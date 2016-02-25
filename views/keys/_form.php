<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\Keys;

/* @var $this yii\web\View */
/* @var $model app\models\Keys */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$keys = Keys::find()->all();
$items = ArrayHelper::map($keys, 'id', 'title');
$options = ['prompt' => ''];

?>


<div class="keys-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList($items, $options); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
