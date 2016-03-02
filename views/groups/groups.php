<?php

/**
 * @var $isNewRecord bool
 * @var $mode GroupsForm
 */

use app\models\GroupsForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Projects;
use yii\helpers\ArrayHelper;

?>

<?php
if (Yii::$app->request->get('project_id'))
    $project_id = Yii::$app->request->get('project_id');
else
    $project_id = null;
?>


<div class="groups-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= ($isNewRecord) ? '' : $form->field($model, 'id', ['template' => '{input}'])->hiddenInput(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([
        '1' => Yii::t('app', 'Активно'),
        '0' => Yii::t('app', 'Неактивно')
    ]) ?>

    <?= $form->field($model, 'project_id')->dropDownList(ArrayHelper::map(Projects::find()->all(), 'id', 'title'),
        ['prompt' => Yii::t('app', 'Выберите проект'),
        'options' => [
            $project_id => ['selected' => true]
        ]
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($isNewRecord ? Yii::t('app', 'Создать') : Yii::t('app', 'Обновить'), ['class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>



</div>
