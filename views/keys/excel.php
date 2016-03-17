<?php

use \moonland\phpexcel\Excel;


    Excel::export([
        'models' => $model,
        'columns' => [
            'key_id',
            [
                'attribute' => 'key_id',
                'header' => 'Project title',
                'value' => function($model){
                    return $model->group->project->title;
                },
            ],
            [
                'attribute' => 'key_id',
                'header' => 'Group',
                'value' => function($model){
                    return $model->group->title;
                },
            ],
            [
                'attribute' => 'key_id',
                'header' => 'Title',
                'value' => function($model){
                    return $model->title;
                },
            ],
            [
                'attribute' => 'date',
                'header' => 'Date',
                'format' => 'text',
                'value' => function($model) {
                    return date('d-m-Y', $model->fullDAte);
                },
            ],
            [
                'attribute' => 'position',
                'value' => function($model) {
                    return $model->position;
                }
            ],


        ],
        'headers' => [
            'key_id' => 'id',
        ],
    ]);