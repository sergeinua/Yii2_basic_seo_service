<?php

use \moonland\phpexcel\Excel;
echo '<meta charset=utf-8>';
$objPHPExcel = new \PHPExcel();

$sheet=0;

$objPHPExcel->setActiveSheetIndex($sheet);
$foos = [
    ['firstname'=>'John',
        'lastname'=>'Doe'],
    ['firstname'=>'John',
        'lastname'=>'Jones'],
    ['firstname'=>'Jane',
        'lastname'=>'Doe'],
];


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);

$objPHPExcel->getActiveSheet()->setTitle('xxx')
    ->setCellValue('A1', 'Firstname')
    ->setCellValue('B1', 'Lastname');

$row=2;

foreach ($foos as $foo) {

    $objPHPExcel->getActiveSheet()->setCellValue('A'.$row,$foo['firstname']);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$row,$foo['lastname']);
    $row++ ;
}


header('Content-Type: application/vnd.ms-excel');
$filename = "MyExcelReport_".date("d-m-Y-His").".xls";
//header('Content-Disposition: attachment;filename='.$filename .' ');
header('Cache-Control: max-age=0');
$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');





























//
//    Excel::export([
//        'models' => $model,
//        'columns' => [
//            'key_id',
//            [
//                'attribute' => 'key_id',
//                'header' => 'Project title',
//                'value' => function($model){
//                    return $model->group->project->title;
//                },
//            ],
//            [
//                'attribute' => 'key_id',
//                'header' => 'Group',
//                'value' => function($model){
//                    return $model->group->title;
//                },
//            ],
//            [
//                'attribute' => 'key_id',
//                'header' => 'Title',
//                'value' => function($model){
//                    return $model->title;
//                },
//            ],
//            [
//                'attribute' => 'date',
//                'header' => 'Date',
//                'format' => 'text',
//                'value' => function($model) {
//                    return date('d-m-Y', $model->fullDAte);
//                },
//            ],
//            [
//                'attribute' => 'position',
//                'value' => function($model) {
//                    return $model->position;
//                }
//            ],
//
//
//        ],
//        'headers' => [
//            'key_id' => 'id',
//        ],
//    ]);