<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ApiBrowser */

$this->title = 'Create Api Browser';
$this->params['breadcrumbs'][] = ['label' => 'Api Browsers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="api-browser-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
