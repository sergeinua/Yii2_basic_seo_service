<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProdvigatorOrganic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="prodvigator-organic-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput() ?>

    <?= $form->field($model, 'region_queries_count')->textInput() ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'keyword')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'right_spell')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dynamic')->textInput() ?>

    <?= $form->field($model, 'found_results')->textInput() ?>

    <?= $form->field($model, 'url_crc')->textInput() ?>

    <?= $form->field($model, 'cost')->textInput() ?>

    <?= $form->field($model, 'concurrency')->textInput() ?>

    <?= $form->field($model, 'position')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'keyword_id')->textInput() ?>

    <?= $form->field($model, 'subdomain')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'region_queries_count_wide')->textInput() ?>

    <?= $form->field($model, 'types')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'geo_names')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
