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

<?php
    if (Yii::$app->request->get('group_id'))
        $group_id = Yii::$app->request->get('group_id');
    else
        $group_id = null;
?>

<div class="keys-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= ($isNewRecord) ? '' : $form->field($model, 'id', ['template' => '{input}'])->hiddenInput(); ?>

    <!--?//= $form->field($model, 'title')->textInput(['maxlength' => true]) ?-->
    <?= $form->field($model, 'title')->textArea(['rows' => '6']) ?>

    <?= $form->field($model, 'status')->dropDownList([
        '1' => Yii::t('app', 'Активно'),
        '0' => Yii::t('app', 'Неактивно'),
    ]) ?>

    <?= $form->field($model, 'group_id')->dropDownList(ArrayHelper::map(Groups::find()->all(), 'id', 'title'),
        ['prompt' => Yii::t('app', 'Выберите группу'),
        'options' => [
                $group_id => ['selected' => true]
            ]
        ]
        ) ?>

    <div class="form-group">
        <?= Html::submitButton($isNewRecord ? Yii::t('app', 'Создать') : Yii::t('app', 'Обновить'), ['class' => $isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>
