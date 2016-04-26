<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use app\models\GroupKey;
use yii\data\ActiveDataProvider;
use app\models\Keys;
use yii\helpers\Url;
use miloschuman\highcharts\Highcharts;
use yii\helpers\ArrayHelper;
use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Groups */

$this->title = $model->title;
//$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<?php // applying the correct timezone
    date_default_timezone_set('Europe/Kiev');
?>

<?php //sorting the results
$sorted_model = [];
$i=0;

$sort = Yii::$app->request->get('sort_by');
if(isset($sort)){
        switch($sort){
            case 'key_word':
                foreach ($model->keys as $key) {
                    $sorted_model[$i]['title'] = $key->title;
                    $sorted_model[$i]['position'] = $key->position->position;
                    $sorted_model[$i]['prev_position'] = $key->previousPosition['1']['position'];
                    $sorted_model[$i]['full_date'] = $key->position->fullDate;
                    $sorted_model[$i]['key_id'] = $key->id;
                    $i++;
                }
                sort($sorted_model);
                break;
            case 'key_word_desc':
                foreach ($model->keys as $key) {
                    $sorted_model[$i]['title'] = $key->title;
                    $sorted_model[$i]['position'] = $key->position->position;
                    $sorted_model[$i]['prev_position'] = $key->previousPosition['1']['position'];
                    $sorted_model[$i]['full_date'] = $key->position->fullDate;
                    $sorted_model[$i]['key_id'] = $key->id;
                    $i++;
                }
                sort($sorted_model);
                $sorted_model = array_reverse($sorted_model);
                break;
            case 'position':
                foreach ($model->keys as $key) {
                    $sorted_model[$i]['position'] = $key->position->position;
                    $sorted_model[$i]['title'] = $key->title;
                    $sorted_model[$i]['prev_position'] = $key->previousPosition['1']['position'];
                    $sorted_model[$i]['full_date'] = $key->position->fullDate;
                    $sorted_model[$i]['key_id'] = $key->id;
                    $i++;
                }
                sort($sorted_model);
                break;
            case 'position_desc':
                foreach ($model->keys as $key) {
                    $sorted_model[$i]['position'] = $key->position->position;
                    $sorted_model[$i]['title'] = $key->title;
                    $sorted_model[$i]['prev_position'] = $key->previousPosition['1']['position'];
                    $sorted_model[$i]['full_date'] = $key->position->fullDate;
                    $sorted_model[$i]['key_id'] = $key->id;
                    $i++;
                }
                sort($sorted_model);
                $sorted_model = array_reverse($sorted_model);
                break;
            case 'last_updated':
                foreach ($model->keys as $key) {
                    $sorted_model[$i]['full_date'] = $key->position->fullDate;
                    $sorted_model[$i]['position'] = $key->position->position;
                    $sorted_model[$i]['title'] = $key->title;
                    $sorted_model[$i]['prev_position'] = $key->previousPosition['1']['position'];
                    $sorted_model[$i]['key_id'] = $key->id;
                    $i++;
                }
                sort($sorted_model);
                $sorted_model = array_reverse($sorted_model);
                break;
            case 'last_updated_desc':
                foreach ($model->keys as $key) {
                    $sorted_model[$i]['full_date'] = $key->position->fullDate;
                    $sorted_model[$i]['position'] = $key->position->position;
                    $sorted_model[$i]['title'] = $key->title;
                    $sorted_model[$i]['prev_position'] = $key->previousPosition['1']['position'];
                    $sorted_model[$i]['key_id'] = $key->id;
                    $i++;
                }
                sort($sorted_model);
                break;
        }


} else {
    // sorting not needed
    foreach ($model->keys as $key) {
        $sorted_model[$i]['key_id'] = $key->id;
        $sorted_model[$i]['title'] = $key->title;
        $sorted_model[$i]['position'] = isset($key->position->position) ? $key->position->position : null;
        $sorted_model[$i]['prev_position'] = isset($key->previousPosition['1']['position']) ? $key->previousPosition['1']['position'] : null;
        $sorted_model[$i]['full_date'] = isset($key->position->fullDate) ? $key->position->fullDate : null;
        $i++;
    }
    sort($sorted_model);
}

?>

<div class="groups-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Назад'), Yii::$app->request->referrer, ['class' => 'btn btn-primary']); ?>
        <?= Html::a(Yii::t('app', 'Обновить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']); ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]); ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'description',
                'value' => isset($model->description) ? $model->description : '',
            ],
            [
                'attribute' => 'status',
                'value' => $model->status == 0 ? Yii::t('app', 'Неактивно') : Yii::t('app', 'Активно'),
            ],
            'googlehost',
            'language',
        ],
    ]) ?>

    <h2><?= Yii::t('app', 'Ключевые слова группы'); ?></h2>

    <?= Html::a(Yii::t('app', 'Добавить ключевое слово'), ['/keys/create', 'group_id' => Yii::$app->request->get('id')], ['class'=>'btn btn-primary']) ?>

    <?php if($sorted_model) : ?>

        <?= Html::a(Yii::t('app', 'Обновить все ключевые слова группы'), ['/keys/update-all-keys', 'group_id' => Yii::$app->request->get('id')], ['class'=>'btn btn-primary']) ?>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th><a><?= Html::a('<span>' . Yii::t("app", "Ключевые слова") . '</span>', ['/groups/view',
                                'id' => Yii::$app->request->get('id'),
                                //if exists => sort desc
                                'sort_by' => Yii::$app->request->get('sort_by') === 'key_word' ? 'key_word_desc' : 'key_word',
                            ]) ?>
                        </a>
                    </th>
                    <th><?= Yii::t('app', 'Действия'); ?></th>
                    <th><a><?= Html::a('<span>' . Yii::t("app", "Позиция") . '</span>', ['/groups/view',
                                'id' => Yii::$app->request->get('id'),
                                'sort_by' => Yii::$app->request->get('sort_by') === 'position' ? 'position_desc' : 'position',
                            ]) ?>
                        </a>
                    </th>
                    <th></th>
                    <th><a><?= Html::a('<span>' . Yii::t("app", "Последнее обновление") . '</span>', ['/groups/view',
                                'id' => Yii::$app->request->get('id'),
                                'sort_by' => Yii::$app->request->get('sort_by') === 'last_updated' ? 'last_updated_desc' : 'last_updated',
                            ]) ?>
                        </a>
                    </th>
                    <th><?= Yii::t('app', 'Обновить'); ?></th>
                </tr>
            </thead>
            <tbody>

            <?php $i=0; ?>
            <?php for($i=0; $i<count($sorted_model); $i++) { ?>

                <tr>
                    <td><?= $sorted_model[$i]['key_id'] ?></td>
                    <td><?= Html::a('<span>' . $sorted_model[$i]['title'] . '</span>', ['/keys/view', 'id' => $sorted_model[$i]['key_id']]) ?></td>
                    <td>
                        <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['/keys/view', 'id' => $sorted_model[$i]['key_id']]) ?>
                        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['/keys/update', 'id' => $sorted_model[$i]['key_id']]) ?>
                        <?= Html::a('<span class="glyphicon glyphicon-remove"></span>', ['/keys/delete', 'id' => $sorted_model[$i]['key_id']], [
                            'data' => [
                                'confirm' => 'Are you sure?',
                                'method' => 'post',
                            ]
                        ]) ?>
                    </td>
                    <td>
                        <?php if(isset($sorted_model[$i]['position'])) : ?>
                            <?= $sorted_model[$i]['position'] ?>
                        <?php else : ?>
                            -
                        <?php endif; ?>
                    </td>
                    <?php if(isset($sorted_model[$i]['prev_position'])) : ?>

                        <?php $result = -( (int)$sorted_model[$i]['position'] - (int)$sorted_model[$i]['prev_position'] ); ?>
                        <?php if($result > 0) : ?>
                            <td style="color: green">
                                <?php $result = '+' . $result; ?>
                                <?php echo $result; ?>
                            </td>
                        <?php endif; ?>
                        <?php if($result == 0) : ?>
                            <td>
                            </td>
                        <?php endif; ?>
                        <?php if($result < 0) : ?>
                            <td style="color: red">
                                <?php echo $result; ?>
                            </td>
                        <?php endif; ?>

                    <?php else : ?>
                        <td></td>
                    <?php endif; ?>
                    <td>
                        <?= isset($sorted_model[$i]['full_date']) ? date("F j, Y, g:i a",  $sorted_model[$i]['full_date']) : ''; ?>
                    </td>
                    <td>
                        <?= Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['/keys/update-single-key',
                            'key_id' => $sorted_model[$i]['key_id'],
                            'project_link' => $model->project->title,
                            'group_id' => $model->id,
                        ]) ?>
                    </td>

                </tr>
            <?php } ?>
            </tbody>
        </table>

        <?php
        global $first;
        global $second;
        global $third;
        global $fourth;
        global $fifth;
        global $sixth;
        global $seventh;
        global $eighth;
        global $ninth;

        if($model->keys) {
            foreach ($model->keys as $key) {
                if(isset($key->position->position)) {
                    if ($key->position->position <= 3)
                        $first++;
                    if ($key->position->position > 3 && $key->position->position <= 10)
                        $second++;
                    if ($key->position->position <= 10)
                        $third++;
                    if ($key->position->position >= 11 && $key->position->position <= 20)
                        $fourth++;
                    if ($key->position->position >= 21 && $key->position->position <= 50)
                        $fifth++;
                    if ($key->position->position >= 21 && $key->position->position <= 50)
                        $sixth++;
                    if ($key->position->position < 100)
                        $seventh++;
                    if ($key->position->position > 100)
                        $eighth++;
                }
            }
        }?>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>1 - 3</th>
                    <th>4 - 10</th>
                    <th>1 - 10</th>
                    <th>11 - 20</th>
                    <th>21 - 50</th>
                    <th>51 - 100</th>
                    <th>< 100</th>
                    <th>> 100</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo isset($first) ? $first : '0'; ?></td>
                    <td><?php echo isset($second) ? $second : '0'; ?></td>
                    <td><?php echo isset($third) ? $third : '0'; ?></td>
                    <td><?php echo isset($fourth) ? $fourth : '0'; ?></td>
                    <td><?php echo isset($fifth) ? $fifth : '0'; ?></td>
                    <td><?php echo isset($sixth) ? $sixth : '0'; ?></td>
                    <td><?php echo isset($seventh) ? $seventh : '0'; ?></td>
                    <td><?php echo isset($eighth) ? $eighth : '0'; ?></td>
                </tr>
            </tbody>
        </table>

    <?php endif; ?>

    <div>
        <h2><?= Yii::t('app', 'Динамика группы'); ?></h2>

        <?= Html::a(Yii::t('app', 'Обновить данные'), ['/group-visibility/update-position', 'group_id' => Yii::$app->request->get('id')], ['class'=>'btn btn-primary']) ?>

        <?= Html::a(Yii::t('app', 'Экспорт в XLS'), ['/keys/excel-group',
            'group_id' => Yii::$app->request->get('id'),
            'period_for_keys_from' => $period_for_keys_from,
            'period_for_keys_till' => $period_for_keys_till,
        ], ['class'=>'btn btn-primary']) ?>

        <?php $form = ActiveForm::begin(); ?>

            <label><?= Yii::t('app', 'Начальная дата'); ?></label>
            <?= DateRangePicker::widget([
                'name'=>'period_for_keys_from',
                'convertFormat'=>true,
                'pluginOptions'=>[
                    'timePicker'=>false,
                    'timePickerIncrement'=>15,
                    'locale'=>['format' => 'Y-m-d'],
                    'singleDatePicker'=>true,
                    'showDropdowns'=>true
                ]
            ]); ?>

            <label><?= Yii::t('app', 'Конечная дата'); ?></label>
            <?= DateRangePicker::widget([
                'name'=>'period_for_keys_till',
                'convertFormat'=>true,
                'pluginOptions'=>[
                    'timePicker'=>false,
                    'timePickerIncrement'=>15,
                    'locale'=>['format' => 'Y-m-d'],
                    'singleDatePicker'=>true,
                    'showDropdowns'=>true
                ]
            ]); ?>

            <div class="form-group">
                <?= Html::submitButton( Yii::t('app', 'Применить'), ['class' => 'btn btn-primary']) ?>
            </div>

        <?php $form = ActiveForm::end(); ?>

        <?php
        $i=0;
        $dates=[];
        $visibility=[];
        for($i=0; $i<count($gr_vis_model); $i++){
            $dates[$i] = date($gr_vis_model[$i]['date']);
            $visibility[$i] = $gr_vis_model[$i]['visibility'];
        }
        // formatting dates
        for($i=0; $i<count($dates); $i++) {
            $dates[$i] = DateTime::createFromFormat('Ymd', $dates[$i])->format('d-m-Y');
        };
        // setting dates the needed way
        $dates = array_reverse($dates);
        $visibility = array_reverse($visibility); ?>

        <?php if($period_for_keys_from || $period_for_keys_till) : ?>
            <div><?= Yii::t('app', 'Выбран период') ?>
                <?php if($period_for_keys_from) : ?>
                    <?= Yii::t('app', 'с') ?>
                    <?= DateTime::createFromFormat('dmY', $period_for_keys_from)->format('d-m-Y') ?>
                <?php endif; ?>
                <?php if($period_for_keys_till) : ?>
                    <?= Yii::t('app', 'по') ?>
                    <?= DateTime::createFromFormat('dmY', $period_for_keys_till)->format('d-m-Y') ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>


        <?= Highcharts::widget([
            'options' => [
                'title' => ['text' => Yii::t("app", "Ключевые слова группы")],
                'xAxis' => [
                    'categories' => $dates,
                ],
                'yAxis' => [
                    'title' => ['text' => Yii::t("app", "Видимость %")]
                ],
                'series' => [
                    ['name' => $this->title, 'data' => $visibility],
                ]
            ]
        ]); ?>
    </div>

</div>
