<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProdvigatorData */

$this->title = 'Create Prodvigator Data';
$this->params['breadcrumbs'][] = ['label' => 'Prodvigator Datas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prodvigator-data-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
