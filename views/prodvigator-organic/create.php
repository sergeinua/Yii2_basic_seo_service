<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProdvigatorOrganic */

$this->title = 'Create Prodvigator Organic';
$this->params['breadcrumbs'][] = ['label' => 'Prodvigator Organics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prodvigator-organic-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
